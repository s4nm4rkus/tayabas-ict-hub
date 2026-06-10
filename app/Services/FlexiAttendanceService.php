<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * FlexiAttendanceService
 *
 * ZKTeco punch slotting rules:
 * ─────────────────────────────────────────────────────────────
 *  The device records every tap identically — no AM/PM label.
 *  We infer the slot from TIME OF DAY + PUNCH COUNT:
 *
 *  Slot boundaries:
 *    AM_IN  window : 00:00 – 11:59  (morning arrival)
 *    AM_OUT window : 00:00 – 11:59  (morning departure — rare, e.g. half-day)
 *    PM_IN  window : 12:00 – 14:59  (return from lunch)
 *    PM_OUT window : 12:00 – 23:59  (afternoon departure)
 *
 *  Assignment algorithm (in punch-count order):
 *    1 punch  → am_time_in only
 *    2 punches:
 *        both before 12:00 → am_in, am_out
 *        first before 12:00, second 12:00+ → am_in, pm_out   ← most common ZKTeco case
 *        both 12:00+        → pm_in, pm_out
 *    3 punches → am_in, pm_in, pm_out
 *    4 punches → am_in, am_out, pm_in, pm_out
 *    5+ punches → use first as am_in, last as pm_out, ignore middle
 *
 * Flexi work rules:
 *    Required  : 8 hours (480 min)
 *    Lunch     : 12:00–13:00 never counted
 *    Late       : clock-in after 09:00
 *    Hard cap  : 18:00 (nothing after counts)
 */
class FlexiAttendanceService
{
    public const REQUIRED_MINUTES = 480;
    public const WORK_START       = '07:00';
    public const LATE_THRESHOLD   = '09:00';
    public const LUNCH_START      = '12:00';
    public const LUNCH_END        = '13:00';
    public const WORK_END         = '18:00';

    // ─────────────────────────────────────────────────────────────
    // Parse ZKTeco .txt / .dat file
    // ─────────────────────────────────────────────────────────────
    public function parseTxtFile(string $content): array
    {
        $rows = [];

        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $line);
            if (count($parts) < 5) {
                continue;
            }

            $empCode     = trim($parts[0]);
            $datetimeStr = trim($parts[1]) . ' ' . trim($parts[2]);
            $punchState  = (int) trim($parts[3]);
            $verifyType  = (int) trim($parts[4]);

            try {
                $punchTime = Carbon::parse($datetimeStr);
            } catch (\Exception $e) {
                continue;
            }

            $rows[] = [
                'emp_code'    => $empCode,
                'punch_time'  => $punchTime,
                'punch_state' => $punchState,
                'verify_type' => $verifyType,
            ];
        }

        return $rows;
    }

    // ─────────────────────────────────────────────────────────────
    // Group raw punches → per-employee per-day computed records
    // ─────────────────────────────────────────────────────────────
    public function groupByEmployeeDay(array $rows): array
    {
        // Bucket punches by emp_code → date
        $buckets = [];
        foreach ($rows as $row) {
            $date = $row['punch_time']->format('Y-m-d');
            $buckets[$row['emp_code']][$date][] = $row['punch_time'];
        }

        $result = [];
        foreach ($buckets as $empCode => $dates) {
            foreach ($dates as $date => $punches) {
                usort($punches, fn ($a, $b) => $a->timestamp <=> $b->timestamp);

                $firstPunch = $punches[0];
                $lastPunch  = count($punches) > 1 ? end($punches) : null;

                $computed = $this->computeDay($date, $firstPunch, $lastPunch, $punches);

                $result[$empCode][$date] = array_merge(
                    ['date' => $date, 'all_punches' => $punches],
                    $computed
                );
            }
        }

        return $result;
    }

    // ─────────────────────────────────────────────────────────────
    // Compute one day's attendance from punches
    // ─────────────────────────────────────────────────────────────
    public function computeDay(
        string $date,
        Carbon $firstPunch,
        ?Carbon $lastPunch,
        array $allPunches = []
    ): array {
        $workStart  = Carbon::parse("$date " . self::WORK_START);
        $lateThresh = Carbon::parse("$date " . self::LATE_THRESHOLD);
        $lunchStart = Carbon::parse("$date " . self::LUNCH_START);
        $lunchEnd   = Carbon::parse("$date " . self::LUNCH_END);
        $workEnd    = Carbon::parse("$date " . self::WORK_END);

        // Clamp to valid window
        $effectiveIn  = $firstPunch->copy()->max($workStart);
        $effectiveOut = $lastPunch ? $lastPunch->copy()->min($workEnd) : null;

        // ── Slot punches into AM/PM columns ─────────────────────
        [$amTimeIn, $amTimeOut, $pmTimeIn, $pmTimeOut] =
            $this->slotPunches($allPunches, $lunchStart);

        // ── Has valid in+out pair? ───────────────────────────────
        $hasTwoPunches = $lastPunch && ! $lastPunch->eq($firstPunch);

        // ── Worked minutes ──────────────────────────────────────
        $workedMinutes = 0;
        if ($hasTwoPunches && $effectiveOut) {
            $workedMinutes = $this->computeWorkedMinutes(
                $effectiveIn,
                $effectiveOut,
                $lunchStart,
                $lunchEnd
            );
        }

        // ── Late minutes ────────────────────────────────────────
        // Late is only meaningful if the employee has a morning punch.
        // If they only punched in the afternoon, late is 0 —
        // the undertime already captures the full shortfall.
        $lateMinutes = 0;
        $lunchStart  = Carbon::parse("$date " . self::LUNCH_START);
        if ($firstPunch->lt($lunchStart) && $firstPunch->gt($lateThresh)) {
            $lateMinutes = (int) $lateThresh->diffInMinutes($firstPunch);
        }

        // ── Undertime minutes ───────────────────────────────────
        $undertimeMinutes = $hasTwoPunches
            ? max(0, self::REQUIRED_MINUTES - $workedMinutes)
            : self::REQUIRED_MINUTES;

        $totalHours  = round($workedMinutes / 60, 2);
        $expectedOut = $this->getExpectedTimeOut($date, $effectiveIn, $lunchEnd, $workEnd);

        return [
            'time_in'           => $firstPunch,
            'time_out'          => $lastPunch,
            'am_time_in'        => $amTimeIn,
            'am_time_out'       => $amTimeOut,
            'pm_time_in'        => $pmTimeIn,
            'pm_time_out'       => $pmTimeOut,
            'total_hours'       => $totalHours,
            'worked_minutes'    => $workedMinutes,
            'late_minutes'      => $lateMinutes,
            'undertime_minutes' => $undertimeMinutes,
            'expected_time_out' => $expectedOut->format('H:i'),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // SLOT PUNCHES into the 4 DTR columns
    // ─────────────────────────────────────────────────────────────
    /**
     * Given sorted punches for one day, assign them to:
     *   [am_time_in, am_time_out, pm_time_in, pm_time_out]
     *
     * Rules:
     *   1 punch  → [in, null, null, null]
     *
     *   2 punches:
     *     both AM  (both < 12:00) → [in, out, null, null]
     *     AM + PM  (first < 12:00, second ≥ 12:00) → [in, null, null, out]
     *       ↑ most common ZKTeco scenario: tap in at 08:00, tap out at 17:00
     *     both PM  (both ≥ 12:00) → [null, null, in, out]
     *
     *   3 punches → [first, null, second, third]
     *     (AM in, then PM in + PM out)
     *
     *   4 punches → [first, second, third, fourth]
     *     (AM in, AM out, PM in, PM out — full manual punching)
     *
     *   5+ punches → [first, null, null, last]
     *     (ignore middle punches — treat as simple in/out)
     */
    private function slotPunches(array $punches, Carbon $lunchStart): array
    {
        $n    = count($punches);
        $fmt  = fn (Carbon $p) => $p->format('H:i');
        $isAM = fn (Carbon $p) => $p->lt($lunchStart);

        if ($n === 0) {
            return [null, null, null, null];
        }

        $first = $punches[0];
        $last  = end($punches);

        if ($n === 1) {
            // Single punch — slot by time of day
            return $isAM($first)
                ? [$fmt($first), null, null, null]   // morning only
                : [null, null, $fmt($first), null];  // afternoon only (e.g. came in after lunch)
        }

        if ($n === 2) {
            $second = $punches[1];

            if ($isAM($first) && $isAM($second)) {
                // Both morning — AM half day
                return [$fmt($first), $fmt($second), null, null];
            }

            if ($isAM($first) && ! $isAM($second)) {
                // Normal full day: tap in AM, tap out PM — most common ZKTeco case
                return [$fmt($first), null, null, $fmt($second)];
            }

            // Both PM — afternoon only (e.g. testing, or came in very late)
            return [null, null, $fmt($first), $fmt($second)];
        }

        if ($n === 3) {
            if ($isAM($first)) {
                // AM in, then 2 afternoon punches = AM in + PM in + PM out
                return [$fmt($punches[0]), null, $fmt($punches[1]), $fmt($punches[2])];
            }
            // All 3 in PM — treat as pm_in + 2 irrelevant middle + pm_out → pm_in, pm_out
            return [null, null, $fmt($first), $fmt($last)];
        }

        if ($n === 4) {
            if ($isAM($first)) {
                // Full four-punch: AM in, AM out, PM in, PM out
                return [$fmt($punches[0]), $fmt($punches[1]), $fmt($punches[2]), $fmt($punches[3])];
            }
            // All 4 in PM — first and last
            return [null, null, $fmt($first), $fmt($last)];
        }

        // 5+ punches — deduplicate by taking first and last punch of each session
        // Check if any punches are in the morning
        $amPunches = array_values(array_filter($punches, $isAM));
        $pmPunches = array_values(array_filter($punches, fn ($p) => ! $isAM($p)));

        $amIn  = count($amPunches) >= 1 ? $fmt($amPunches[0]) : null;
        $amOut = count($amPunches) >= 2 ? $fmt(end($amPunches)) : null;
        $pmIn  = count($pmPunches) >= 2 ? $fmt($pmPunches[0]) : null; // only set if 2+ PM punches
        $pmOut = count($pmPunches) >= 1 ? $fmt(end($pmPunches)) : null;

        // If no AM punches at all, treat first PM as pm_in (not pm_out)
        if (! $amIn && count($pmPunches) >= 2) {
            $pmIn  = $fmt($pmPunches[0]);
            $pmOut = $fmt(end($pmPunches));
        } elseif (! $amIn && count($pmPunches) === 1) {
            $pmIn  = $fmt($pmPunches[0]);
            $pmOut = null;
        }

        return [$amIn, $amOut, $pmIn, $pmOut];
    }

    // ─────────────────────────────────────────────────────────────
    // Compute worked minutes excluding lunch 12:00–13:00
    // ─────────────────────────────────────────────────────────────
    private function computeWorkedMinutes(
        Carbon $in,
        Carbon $out,
        Carbon $lunchStart,
        Carbon $lunchEnd
    ): int {
        // Case A: entirely morning
        if ($out->lte($lunchStart)) {
            return max(0, (int) $in->diffInMinutes($out));
        }

        // Case B: entirely afternoon
        if ($in->gte($lunchEnd)) {
            return max(0, (int) $in->diffInMinutes($out));
        }

        // Case C: spans lunch
        $morningMins   = (int) $in->diffInMinutes($lunchStart);
        $afternoonMins = (int) $lunchEnd->diffInMinutes($out);

        return max(0, $morningMins + $afternoonMins);
    }

    // ─────────────────────────────────────────────────────────────
    // Calculate expected time-out to complete 8 hours
    // ─────────────────────────────────────────────────────────────
    private function getExpectedTimeOut(
        string $date,
        Carbon $effectiveIn,
        Carbon $lunchEnd,
        Carbon $workEnd
    ): Carbon {
        $lunchStart = Carbon::parse("$date " . self::LUNCH_START);

        if ($effectiveIn->lt($lunchStart)) {
            $morningAvailable = (int) $effectiveIn->diffInMinutes($lunchStart);
            $remaining        = self::REQUIRED_MINUTES - $morningAvailable;

            if ($remaining <= 0) {
                return $effectiveIn->copy()->addMinutes(self::REQUIRED_MINUTES);
            }

            $expected = $lunchEnd->copy()->addMinutes($remaining);
        } else {
            $expected = $effectiveIn->copy()->addMinutes(self::REQUIRED_MINUTES);
        }

        return $expected->min($workEnd);
    }

    // ─────────────────────────────────────────────────────────────
    // Format minutes as "2h 30m"
    // ─────────────────────────────────────────────────────────────
    public function formatMinutes(int $minutes): string
    {
        if ($minutes <= 0) {
            return '—';
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        if ($h > 0 && $m > 0) {
            return "{$h}h {$m}m";
        }
        if ($h > 0) {
            return "{$h}h";
        }
        return "{$m}m";
    }
}

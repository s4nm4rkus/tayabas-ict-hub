<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeavePoint;

class LeavePointSeeder extends Seeder
{
    /**
     * ORL Table I — Vacation and Sick Leave Credits Earned.
     *
     * Legal basis: CSC MC No. 41, s. 1998 (Omnibus Rules on Leave), Sec. 27:
     * "Computation of vacation leave and sick leave shall be made on the
     * basis of one day vacation leave and one day sick leave for every 24
     * days of actual service."
     *
     * Verified against two independent sources:
     *  1. Official CSC MC 41 s.1998 text (legal.uplb.edu.ph copy)
     *  2. HR-provided design document (Table 4), which states:
     *     "Full Month = 1.250 VL / 1.250 SL. Partial month, e.g. 20 days
     *      worked → 0.833 VL/SL; 15 days worked → 0.625 VL/SL."
     *
     * Both sources agree exactly with the formula: earned = days ÷ 24
     * for partial months (1-24 days of actual service), and
     * earned = months × 1.25 for whole months (1-12).
     *
     * NOT seeded here: the "leave_earn_wop" (LWOP-reduced earning) column,
     * and the "vacation_leave" column. Their exact intended values/formula
     * are not yet confirmed against an official source — see Section 12,
     * open questions, of the HR design document. Do not compute or guess
     * these; they are deferred until confirmed, per the project's own
     * rule that open legal questions must not be silently hard-coded.
     */
    public function run(): void
    {
        // ── Day-based table: partial month, 1-24 days of actual service ──
        // leave_day = number of days of actual service that month
        // point_equi = VL (and equally, SL) days earned for that service
        for ($day = 1; $day <= 24; $day++) {
            $earned = round($day / 24, 3);

            LeavePoint::updateOrCreate(
                ['leave_day' => (string) $day, 'month' => null],
                [
                    'point_equi'     => number_format($earned, 3, '.', ''),
                    'leave_earn'     => null,
                    'vacation_leave' => null,
                    'leave_earn_wop' => null,
                ]
            );
        }

        // ── Month-based table: whole months, 1-12 ──
        // month = number of full months of service
        // leave_earn = cumulative VL (and equally, SL) days earned
        for ($month = 1; $month <= 12; $month++) {
            $earned = round($month * 1.25, 3);

            LeavePoint::updateOrCreate(
                ['month' => (string) $month, 'leave_day' => null],
                [
                    'leave_earn'     => number_format($earned, 3, '.', ''),
                    'point_equi'     => null,
                    'vacation_leave' => null,
                    'leave_earn_wop' => null,
                ]
            );
        }
    }
}

@extends('layouts.employee')
@section('title', 'My Attendance')
@section('page-title', 'Attendance')

@section('content')

    {{-- ── Page Hero ── --}}
    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">My Records</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Attendance & DTR</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                View your daily time records, late and undertime, and print your official DTR.
            </p>
        </div>
    </div>

    {{-- ── Top bar: month picker + tab nav ── --}}
    <div class="anim-fade-up"
        style="display:flex;align-items:center;justify-content:space-between;
               flex-wrap:wrap;gap:12px;margin-bottom:18px;">

        {{-- Month picker --}}
        <form method="GET" action="{{ route('employee.attendance.index') }}" id="monthPickerForm"
            style="display:flex;align-items:center;gap:8px;">
            <i class="bi bi-calendar3" style="color:var(--text-secondary);font-size:13px;"></i>
            <input type="month" name="month" class="form-control form-control-sm"
                style="font-size:12px;max-width:160px;" value="{{ $month }}"
                onchange="document.getElementById('monthPickerForm').submit()">
        </form>

        {{-- Tab nav --}}
        <div
            style="display:flex;align-items:center;gap:4px;
                    background:var(--bg);border:1px solid var(--border);
                    border-radius:var(--radius-sm);padding:4px;">
            @foreach ([['records', 'bi-clock-history', 'My Records', 'rgba(110,168,254,0.15)', '#4A90E2'], ['summary', 'bi-bar-chart-line', 'Summary', 'rgba(110,168,254,0.15)', '#4A90E2']] as [$tab, $icon, $label, $activeBg, $activeClr])
                <button onclick="switchTab('{{ $tab }}')" id="emptab-{{ $tab }}"
                    style="display:flex;align-items:center;gap:7px;padding:7px 14px;
                           border-radius:6px;font-size:12px;font-weight:600;
                           border:none;cursor:pointer;transition:all 0.15s;white-space:nowrap;
                           background:{{ $tab === 'records' ? $activeBg : 'transparent' }};
                           color:{{ $tab === 'records' ? $activeClr : 'var(--text-secondary)' }};"
                    data-activebg="{{ $activeBg }}" data-activeclr="{{ $activeClr }}">
                    <i class="bi {{ $icon }}" style="font-size:12px;"></i>
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     PANEL: MY RECORDS
    ══════════════════════════════════════════════════════ --}}
    <div id="emppanel-records" class="anim-fade-up">

        {{-- ── 4 Summary stat cards ── --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px;">

            <div class="stat-card" style="padding:16px 14px;text-align:center;">
                <div
                    style="width:38px;height:38px;border-radius:10px;margin:0 auto 8px;
                     background:linear-gradient(135deg,#A78BFA,#7C3AED);
                     display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-star-fill" style="color:white;font-size:15px;"></i>
                </div>
                <div style="font-size:22px;font-weight:700;color:var(--text-primary);line-height:1;">
                    {{ round($allTimePoints, 2) }}
                </div>
                <div
                    style="font-size:10px;color:var(--text-secondary);text-transform:uppercase;
                     letter-spacing:0.06em;margin-top:3px;">
                    Total Points</div>
            </div>

            <div class="stat-card" style="padding:16px 14px;text-align:center;">
                <div
                    style="width:38px;height:38px;border-radius:10px;margin:0 auto 8px;
                     background:linear-gradient(135deg,#6EA8FE,#4A90E2);
                     display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-clock-fill" style="color:white;font-size:15px;"></i>
                </div>
                <div style="font-size:22px;font-weight:700;color:#4A90E2;line-height:1;">
                    {{ number_format($totalHours, 1) }}h
                </div>
                <div
                    style="font-size:10px;color:var(--text-secondary);text-transform:uppercase;
                     letter-spacing:0.06em;margin-top:3px;">
                    Hours This Month</div>
            </div>

            <div class="stat-card" style="padding:16px 14px;text-align:center;">
                @php
                    $lh = intdiv($totalLate, 60);
                    $lm = $totalLate % 60;
                @endphp
                <div
                    style="width:38px;height:38px;border-radius:10px;margin:0 auto 8px;
                     background:{{ $totalLate > 0 ? 'linear-gradient(135deg,#FCD34D,#F59E0B)' : 'linear-gradient(135deg,#6EE7B7,#059669)' }};
                     display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-alarm" style="color:white;font-size:15px;"></i>
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                     color:{{ $totalLate > 0 ? '#B45309' : '#059669' }};line-height:1;">
                    {{ $totalLate > 0 ? ($lh > 0 ? $lh . 'h ' . $lm . 'm' : $lm . 'm') : '—' }}
                </div>
                <div
                    style="font-size:10px;color:var(--text-secondary);text-transform:uppercase;
                     letter-spacing:0.06em;margin-top:3px;">
                    Late This Month</div>
            </div>

            <div class="stat-card" style="padding:16px 14px;text-align:center;">
                @php
                    $uh = intdiv($totalUndertime, 60);
                    $um = $totalUndertime % 60;
                @endphp
                <div
                    style="width:38px;height:38px;border-radius:10px;margin:0 auto 8px;
                     background:{{ $totalUndertime > 0 ? 'linear-gradient(135deg,#FCA5A5,#EF4444)' : 'linear-gradient(135deg,#6EE7B7,#059669)' }};
                     display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-hourglass-split" style="color:white;font-size:15px;"></i>
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                     color:{{ $totalUndertime > 0 ? '#DC2626' : '#059669' }};line-height:1;">
                    {{ $totalUndertime > 0 ? ($uh > 0 ? $uh . 'h ' . $um . 'm' : $um . 'm') : '—' }}
                </div>
                <div
                    style="font-size:10px;color:var(--text-secondary);text-transform:uppercase;
                     letter-spacing:0.06em;margin-top:3px;">
                    Undertime This Month</div>
            </div>

        </div>

        {{-- ── Records card ── --}}
        <div class="stat-card" style="padding:0;overflow:hidden;">

            {{-- Card header --}}
            <div
                style="padding:13px 18px;border-bottom:1px solid var(--border);
                 display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:26px;height:26px;border-radius:7px;background:rgba(139,92,246,0.12);
                         display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-history" style="color:#8B5CF6;font-size:11px;"></i>
                    </div>
                    <div>
                        <span style="font-size:13px;font-weight:700;color:var(--text-primary);">
                            Attendance Records
                        </span>
                        <span style="font-size:11px;color:var(--text-secondary);margin-left:8px;">
                            {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                            &bull; {{ $attendance->count() }} day(s)
                        </span>
                    </div>
                </div>

                {{-- Print DTR --}}
                <a href="{{ route('employee.attendance.dtr', ['month' => $month]) }}" target="_blank"
                    style="display:flex;align-items:center;gap:6px;padding:7px 15px;
                           border-radius:var(--radius-sm);font-size:12px;font-weight:700;
                           background:linear-gradient(135deg,#6EA8FE,#4A90E2);color:white;
                           text-decoration:none;box-shadow:0 2px 8px rgba(74,144,226,0.3);
                           transition:opacity 0.15s;white-space:nowrap;"
                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-printer" style="font-size:12px;"></i> Print DTR
                </a>
            </div>

            {{-- Points info strip --}}
            <div
                style="padding:8px 18px;background:rgba(139,92,246,0.04);
                 border-bottom:1px solid var(--border);font-size:11px;color:var(--text-secondary);">
                <i class="bi bi-info-circle me-1" style="color:#8B5CF6;"></i>
                Points: <strong style="color:var(--text-primary);">(0.42 ÷ 8) × hours worked</strong> per day &bull;
                Full day = <strong style="color:#7C3AED;">0.42 pts</strong> &bull;
                Late after <strong style="color:var(--text-primary);">9:00 AM</strong> &bull;
                Lunch excluded <strong style="color:var(--text-primary);">12:00–1:00 PM</strong> &bull;
                Cap <strong style="color:var(--text-primary);">6:00 PM</strong>
            </div>

            {{-- Table --}}
            <div class="table-responsive" style="max-height:calc(100vh - 480px);min-height:200px;overflow-y:auto;">
                <table class="table mb-0" style="font-size:12px;">
                    <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                        <tr>
                            @foreach (['Date', 'AM In', 'AM Out', 'PM In', 'PM Out', 'Hours', 'Late', 'Undertime', 'Points'] as $th)
                                <th
                                    style="font-size:9px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:9px 8px;border-bottom:2px solid var(--border);
                                    white-space:nowrap;">
                                    {{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendance as $att)
                            @php
                                $isLate = ($att->late_minutes ?? 0) > 0;
                                $isUT = ($att->undertime_minutes ?? 0) > 0;
                                $rowBg = match (true) {
                                    $isLate && $isUT => 'rgba(239,68,68,0.02)',
                                    $isLate => 'rgba(245,158,11,0.02)',
                                    $isUT => 'rgba(239,68,68,0.02)',
                                    default => '',
                                };
                                $fmt = fn($t) => $t ? \Carbon\Carbon::parse($t)->format('g:i A') : '—';
                                $lh = intdiv($att->late_minutes ?? 0, 60);
                                $lm = ($att->late_minutes ?? 0) % 60;
                                $uh = intdiv($att->undertime_minutes ?? 0, 60);
                                $um = ($att->undertime_minutes ?? 0) % 60;
                            @endphp
                            <tr style="background:{{ $rowBg }};">

                                {{-- Date --}}
                                <td style="padding:9px 8px;vertical-align:middle;white-space:nowrap;">
                                    <div style="font-size:11px;font-weight:700;color:var(--text-primary);">
                                        {{ $att->t_date?->format('D') }}
                                    </div>
                                    <div style="font-size:10px;color:var(--text-secondary);">
                                        {{ $att->t_date?->format('M d') }}
                                    </div>
                                </td>

                                {{-- AM In --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($att->am_time_in)
                                        <span
                                            style="font-size:10px;font-weight:600;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(110,168,254,0.12);color:#2563EB;">
                                            {{ $fmt($att->am_time_in) }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);font-size:11px;">—</span>
                                    @endif
                                </td>

                                {{-- AM Out --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($att->am_time_out)
                                        <span
                                            style="font-size:10px;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(110,168,254,0.07);color:#2563EB;">
                                            {{ $fmt($att->am_time_out) }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);font-size:11px;">—</span>
                                    @endif
                                </td>

                                {{-- PM In --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($att->pm_time_in)
                                        <span
                                            style="font-size:10px;font-weight:600;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(245,158,11,0.12);color:#B45309;">
                                            {{ $fmt($att->pm_time_in) }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);font-size:11px;">—</span>
                                    @endif
                                </td>

                                {{-- PM Out --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($att->pm_time_out)
                                        <span
                                            style="font-size:10px;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(245,158,11,0.07);color:#B45309;">
                                            {{ $fmt($att->pm_time_out) }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);font-size:11px;">—</span>
                                    @endif
                                </td>

                                {{-- Hours --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    <span class="status-badge badge-info" style="font-size:10px;">
                                        {{ number_format((float) $att->total_hours, 1) }}h
                                    </span>
                                </td>

                                {{-- Late --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($isLate)
                                        <span
                                            style="font-size:10px;font-weight:600;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(245,158,11,0.12);color:#B45309;">
                                            {{ $lh > 0 ? $lh . 'h ' : '' }}{{ $lm }}m
                                        </span>
                                    @else
                                        <i class="bi bi-check-circle-fill" style="font-size:12px;color:#22C55E;"></i>
                                    @endif
                                </td>

                                {{-- Undertime --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    @if ($isUT)
                                        <span
                                            style="font-size:10px;font-weight:600;padding:2px 5px;border-radius:4px;
                                              white-space:nowrap;background:rgba(239,68,68,0.12);color:#DC2626;">
                                            {{ $uh > 0 ? $uh . 'h ' : '' }}{{ $um }}m
                                        </span>
                                    @else
                                        <i class="bi bi-check-circle-fill" style="font-size:12px;color:#22C55E;"></i>
                                    @endif
                                </td>

                                {{-- Points --}}
                                <td style="padding:9px 6px;vertical-align:middle;">
                                    <span style="font-size:10px;font-weight:700;color:#059669;">
                                        {{ number_format(round((0.42 / 8) * (float) $att->total_hours, 4), 4) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;padding:4rem;color:var(--text-secondary);">
                                    <i class="bi bi-calendar-x"
                                        style="font-size:28px;display:block;margin-bottom:10px;opacity:0.25;"></i>
                                    <div style="font-size:12px;font-weight:600;">
                                        No attendance records for
                                        {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer totals --}}
            @if ($attendance->count() > 0)
                <div
                    style="padding:10px 16px;border-top:1px solid var(--border);background:var(--bg);
                     display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <div style="display:flex;align-items:center;gap:14px;font-size:11px;flex-wrap:wrap;">
                        <span style="color:var(--text-secondary);">
                            <strong style="color:var(--text-primary);">{{ $attendance->count() }}</strong> day(s)
                        </span>
                        <span>
                            <strong style="color:#4A90E2;">{{ number_format($totalHours, 2) }}h</strong>
                        </span>
                        @if ($totalLate > 0)
                            <span>
                                Late: <strong style="color:#B45309;">
                                    {{ intdiv($totalLate, 60) > 0 ? intdiv($totalLate, 60) . 'h ' : '' }}{{ $totalLate % 60 }}m
                                </strong>
                            </span>
                        @endif
                        @if ($totalUndertime > 0)
                            <span>
                                UT: <strong style="color:#DC2626;">
                                    {{ intdiv($totalUndertime, 60) > 0 ? intdiv($totalUndertime, 60) . 'h ' : '' }}{{ $totalUndertime % 60 }}m
                                </strong>
                            </span>
                        @endif
                        <span>
                            <strong style="color:#059669;">{{ number_format($totalPoints, 4) }} pts</strong>
                        </span>
                    </div>
                    <a href="{{ route('employee.attendance.dtr', ['month' => $month]) }}" target="_blank"
                        style="font-size:11px;font-weight:600;color:#4A90E2;text-decoration:none;
                               display:flex;align-items:center;gap:5px;padding:5px 10px;
                               border-radius:var(--radius-sm);border:1px solid rgba(110,168,254,0.3);
                               background:rgba(110,168,254,0.06);transition:all 0.15s;"
                        onmouseover="this.style.background='rgba(110,168,254,0.13)'"
                        onmouseout="this.style.background='rgba(110,168,254,0.06)'">
                        <i class="bi bi-printer" style="font-size:11px;"></i> Print DTR
                    </a>
                </div>
            @endif

        </div>{{-- end records card --}}

    </div>{{-- /emppanel-records --}}


    {{-- ══════════════════════════════════════════════════════
     PANEL: MY SUMMARY — personal monthly breakdown
    ══════════════════════════════════════════════════════ --}}
    <div id="emppanel-summary" style="display:none;" class="anim-fade-up">

        <div class="stat-card" style="padding:0;overflow:hidden;">

            {{-- Header --}}
            <div
                style="padding:13px 18px;border-bottom:1px solid var(--border);
                 display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:26px;height:26px;border-radius:7px;background:rgba(110,168,254,0.12);
                         display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bar-chart-line" style="color:#4A90E2;font-size:11px;"></i>
                    </div>
                    <div>
                        <span style="font-size:13px;font-weight:700;color:var(--text-primary);">
                            My Monthly Summary
                        </span>
                        <span style="font-size:11px;color:var(--text-secondary);margin-left:8px;">
                            {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('employee.attendance.dtr', ['month' => $month]) }}" target="_blank"
                    style="display:flex;align-items:center;gap:6px;padding:7px 15px;
                           border-radius:var(--radius-sm);font-size:12px;font-weight:700;
                           background:linear-gradient(135deg,#6EA8FE,#4A90E2);color:white;
                           text-decoration:none;box-shadow:0 2px 8px rgba(74,144,226,0.3);
                           transition:opacity 0.15s;"
                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-printer" style="font-size:12px;"></i> Print DTR
                </a>
            </div>

            {{-- Summary table --}}
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:var(--bg);">
                            <th
                                style="padding:10px 18px;text-align:left;font-size:10px;font-weight:700;
                                 color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                 border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Metric</th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                 color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                 border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Value</th>
                            <th
                                style="padding:10px 18px;text-align:left;font-size:10px;font-weight:700;
                                 color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                 border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Note</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $rows = $attendance;
                            $daysPresent = $rows->count();
                            $daysLate = $rows->where('late_minutes', '>', 0)->count();
                            $daysUndertime = $rows->where('undertime_minutes', '>', 0)->count();
                            $daysComplete = $rows->where('late_minutes', 0)->where('undertime_minutes', 0)->count();
                            $avgHours = $daysPresent > 0 ? round($totalHours / $daysPresent, 2) : 0;

                            $summaryRows = [
                                [
                                    'Days Present',
                                    $daysPresent . ' day(s)',
                                    $daysPresent > 0 ? 'Attendance recorded this month' : 'No records yet',
                                    '#22C55E',
                                    'bi-calendar-check',
                                ],

                                [
                                    'Total Hours Worked',
                                    number_format($totalHours, 2) . 'h',
                                    'Lunch excluded · capped at 6:00 PM',
                                    '#4A90E2',
                                    'bi-clock-fill',
                                ],

                                [
                                    'Average Hours/Day',
                                    $avgHours . 'h',
                                    $avgHours >= 8 ? 'Meeting daily requirement' : 'Below 8h average',
                                    $avgHours >= 8 ? '#22C55E' : '#F59E0B',
                                    'bi-graph-up',
                                ],

                                [
                                    'Days On Time',
                                    $daysComplete . ' day(s)',
                                    'No late & no undertime',
                                    '#22C55E',
                                    'bi-check-circle-fill',
                                ],

                                [
                                    'Days Late',
                                    $daysLate . ' day(s)',
                                    $daysLate > 0 ? 'Arrived after 9:00 AM' : 'Always on time this month',
                                    $daysLate > 0 ? '#F59E0B' : '#22C55E',
                                    'bi-alarm',
                                ],

                                [
                                    'Total Late Time',
                                    $totalLate > 0
                                        ? (intdiv($totalLate, 60) > 0 ? intdiv($totalLate, 60) . 'h ' : '') .
                                            $totalLate % 60 .
                                            'm (' .
                                            $totalLate .
                                            ' min)'
                                        : '—',
                                    $totalLate > 0 ? 'Accumulated late minutes' : 'No late minutes',
                                    $totalLate > 0 ? '#B45309' : '#22C55E',
                                    'bi-hourglass',
                                ],

                                [
                                    'Days with Undertime',
                                    $daysUndertime . ' day(s)',
                                    $daysUndertime > 0 ? 'Worked less than 8 hours' : 'Full hours every day',
                                    $daysUndertime > 0 ? '#EF4444' : '#22C55E',
                                    'bi-hourglass-split',
                                ],

                                [
                                    'Total Undertime',
                                    $totalUndertime > 0
                                        ? (intdiv($totalUndertime, 60) > 0 ? intdiv($totalUndertime, 60) . 'h ' : '') .
                                            $totalUndertime % 60 .
                                            'm (' .
                                            $totalUndertime .
                                            ' min)'
                                        : '—',
                                    $totalUndertime > 0 ? 'Accumulated undertime' : 'No undertime this month',
                                    $totalUndertime > 0 ? '#DC2626' : '#22C55E',
                                    'bi-hourglass-split',
                                ],

                                [
                                    'Points Earned (Month)',
                                    number_format($totalPoints, 4) . ' pts',
                                    'Based on hours worked this month',
                                    '#7C3AED',
                                    'bi-star-fill',
                                ],

                                [
                                    'All-Time Points',
                                    number_format($allTimePoints, 2) . ' pts',
                                    'Accumulated across all months',
                                    '#7C3AED',
                                    'bi-trophy-fill',
                                ],
                            ];
                        @endphp

                        @foreach ($summaryRows as $i => [$metric, $value, $note, $color, $icon])
                            <tr style="background:{{ $i % 2 ? 'rgba(0,0,0,0.013)' : '' }};
                                 transition:background 0.1s;"
                                onmouseover="this.style.background='rgba(110,168,254,0.04)'"
                                onmouseout="this.style.background='{{ $i % 2 ? 'rgba(0,0,0,0.013)' : '' }}'">

                                <td style="padding:12px 18px;border-bottom:1px solid var(--border);">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div
                                            style="width:26px;height:26px;border-radius:7px;flex-shrink:0;
                                             background:{{ $color }}1a;
                                             display:flex;align-items:center;justify-content:center;">
                                            <i class="bi {{ $icon }}"
                                                style="font-size:11px;color:{{ $color }};"></i>
                                        </div>
                                        <span style="font-size:12px;font-weight:600;color:var(--text-primary);">
                                            {{ $metric }}
                                        </span>
                                    </div>
                                </td>

                                <td
                                    style="padding:12px 18px;border-bottom:1px solid var(--border);
                                     text-align:center;">
                                    <span
                                        style="font-size:14px;font-weight:800;color:{{ $color }};
                                          letter-spacing:-0.3px;">
                                        {{ $value }}
                                    </span>
                                </td>

                                <td style="padding:12px 18px;border-bottom:1px solid var(--border);">
                                    <span style="font-size:11px;color:var(--text-secondary);">
                                        {{ $note }}
                                    </span>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>{{-- end summary card --}}

    </div>{{-- /emppanel-summary --}}


    @push('scripts')
        <script>
            function switchTab(tab) {
                ['records', 'summary'].forEach(t => {
                    document.getElementById('emppanel-' + t).style.display = 'none';
                    const btn = document.getElementById('emptab-' + t);
                    btn.style.background = 'transparent';
                    btn.style.color = 'var(--text-secondary)';
                });
                document.getElementById('emppanel-' + tab).style.display = 'block';
                const btn = document.getElementById('emptab-' + tab);
                btn.style.background = btn.dataset.activebg;
                btn.style.color = btn.dataset.activeclr;
            }
        </script>
    @endpush

@endsection

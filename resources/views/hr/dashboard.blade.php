@extends('layouts.hr')
@section('title', 'HR Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Hero --}}
    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;" id="hero-date">
                {{ now()->format('l, F d Y') }}
            </div>
            <h4 style="font-size:22px;font-weight:700;margin-bottom:4px;" id="hero-greeting">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
                {{ explode('@', Auth::user()->username)[0] }} 👋
            </h4>
            <p style="font-size:13.5px;opacity:0.8;margin:0;">
                Here's your HR activity overview for today.
            </p>
        </div>
    </div>

    <script>
        (function() {
            const name = "{{ explode('@', Auth::user()->username)[0] }}";

            function updateHero() {
                const now = new Date();
                const hour = now.getHours();
                const greeting = hour < 12 ? 'morning' : hour < 18 ? 'afternoon' : 'evening';
                document.getElementById('hero-greeting').innerHTML = `Good ${greeting}, ${name} 👋`;
                const dateStr = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit'
                });
                const timeStr = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('hero-date').textContent = `${dateStr} · ${timeStr}`;
            }
            updateHero();
            setInterval(updateHero, 1000);
        })();
    </script>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3 anim-fade-up delay-1">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Pending Leaves</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $pendingLeaves }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">awaiting approval</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-calendar-check" style="font-size:20px;color:#F59E0B;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <a href="{{ route('hr.leave.index') }}"
                        style="font-size:12px;font-weight:600;color:#F59E0B;text-decoration:none;">
                        Review <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 anim-fade-up delay-2">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Pending Certs</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $pendingCerts }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">to process</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(52,211,153,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-file-earmark-text" style="font-size:20px;color:#059669;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <a href="{{ route('hr.certificates.index') }}"
                        style="font-size:12px;font-weight:600;color:#059669;text-decoration:none;">
                        Process <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 anim-fade-up delay-3">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Total Employees</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $totalEmployees }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">registered staff</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(110,168,254,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-people" style="font-size:20px;color:#4A90E2;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <a href="{{ route('hr.employees.index') }}"
                        style="font-size:12px;font-weight:600;color:#4A90E2;text-decoration:none;">
                        View all <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 anim-fade-up delay-4">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Present Today</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $presentToday }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">attendance records</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(139,92,246,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock-history" style="font-size:20px;color:#8B5CF6;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <a href="{{ route('hr.attendance.index') }}"
                        style="font-size:12px;font-weight:600;color:#8B5CF6;text-decoration:none;">
                        View <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-3 mb-4 anim-fade-up delay-5">
        <div class="col-lg-8">
            <div class="stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Pending Leave Requests</div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Requires your approval</div>
                    </div>
                    <a href="{{ route('hr.leave.index') }}" class="btn btn-outline-primary btn-sm">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Start</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $leave->fullname }}</div>
                                        <div style="font-size:12px;color:var(--text-secondary);">{{ $leave->position }}
                                        </div>
                                    </td>
                                    <td>{{ $leave->leavetype }}</td>
                                    <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                                    <td><span class="status-badge badge-info">{{ $leave->total_days }} days</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                        No pending leaves.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card h-100">
                <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Announcements
                </div>
                @forelse($announcements as $post)
                    <div style="padding:10px 0;border-bottom:1px solid var(--border);">
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:3px;">
                            {{ $post->title }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">
                            {{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }} · {{ $post->role }}
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:1.5rem 0;color:var(--text-secondary);font-size:13px;">No
                        announcements yet.</div>
                @endforelse
                <a href="{{ route('hr.board.index') }}"
                    style="display:block;margin-top:10px;font-size:12px;font-weight:600;color:#059669;text-decoration:none;">
                    View notice board <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Analytics Section Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3 anim-fade-up delay-6">
        <div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);">Analytics Overview</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                Data insights · last updated {{ now()->format('M d, Y') }}
            </div>
        </div>
    </div>

    {{-- ROW 1: Attendance Breakdown + Leave Types + Cert Volume --}}
    <div class="row g-3 mb-3 anim-fade-up delay-6">

        {{-- Attendance Breakdown --}}
        <div class="col-lg-5">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">Attendance This Month</div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                            {{ now()->format('F Y') }} · {{ $attendanceStats['total_records'] }} records
                        </div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(52,211,153,0.12);
                            display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bar-chart-line" style="color:#059669;font-size:16px;"></i>
                    </div>
                </div>

                @php
                    $total = max($attendanceStats['total_records'], 1);
                    $onTimePct = round(($attendanceStats['on_time'] / $total) * 100);
                    $latePct = round(($attendanceStats['late'] / $total) * 100);
                    $absentPct = round(($attendanceStats['absent'] / $total) * 100);
                @endphp

                <div style="margin-bottom:14px;">
                    <div class="d-flex justify-content-between mb-1" style="font-size:12.5px;">
                        <span style="font-weight:600;color:var(--text-primary);">
                            <span
                                style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#22C55E;margin-right:6px;"></span>On-Time
                        </span>
                        <span style="color:var(--text-secondary);">{{ $attendanceStats['on_time'] }} <span
                                style="opacity:0.6;">({{ $onTimePct }}%)</span></span>
                    </div>
                    <div style="height:7px;border-radius:99px;background:var(--border);overflow:hidden;">
                        <div
                            style="height:100%;width:{{ $onTimePct }}%;background:linear-gradient(90deg,#4ADE80,#22C55E);border-radius:99px;transition:width 1s ease;">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:14px;">
                    <div class="d-flex justify-content-between mb-1" style="font-size:12.5px;">
                        <span style="font-weight:600;color:var(--text-primary);">
                            <span
                                style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#F59E0B;margin-right:6px;"></span>Late
                        </span>
                        <span style="color:var(--text-secondary);">{{ $attendanceStats['late'] }} <span
                                style="opacity:0.6;">({{ $latePct }}%)</span></span>
                    </div>
                    <div style="height:7px;border-radius:99px;background:var(--border);overflow:hidden;">
                        <div
                            style="height:100%;width:{{ $latePct }}%;background:linear-gradient(90deg,#FCD34D,#F59E0B);border-radius:99px;">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:18px;">
                    <div class="d-flex justify-content-between mb-1" style="font-size:12.5px;">
                        <span style="font-weight:600;color:var(--text-primary);">
                            <span
                                style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#EF4444;margin-right:6px;"></span>Absent
                        </span>
                        <span style="color:var(--text-secondary);">{{ $attendanceStats['absent'] }} <span
                                style="opacity:0.6;">({{ $absentPct }}%)</span></span>
                    </div>
                    <div style="height:7px;border-radius:99px;background:var(--border);overflow:hidden;">
                        <div
                            style="height:100%;width:{{ $absentPct }}%;background:linear-gradient(90deg,#FCA5A5,#EF4444);border-radius:99px;">
                        </div>
                    </div>
                </div>

                <div
                    style="padding:10px 14px;border-radius:10px;background:rgba(52,211,153,0.07);
                        border:1px solid rgba(52,211,153,0.14);display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-clock" style="color:#059669;font-size:15px;"></i>
                    <div style="font-size:12.5px;color:var(--text-secondary);">Avg. hours/day this month</div>
                    <div style="margin-left:auto;font-size:15px;font-weight:700;color:var(--text-primary);">
                        {{ number_format($attendanceStats['avg_hours'], 1) }}h
                    </div>
                </div>
            </div>
        </div>

        {{-- Leave Type Breakdown --}}
        <div class="col-lg-4">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">Type of Leave</div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">This year · all statuses
                        </div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,0.12);
                            display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pie-chart" style="color:#F59E0B;font-size:16px;"></i>
                    </div>
                </div>

                @php
                    $leaveTotal = $leaveTypeStats->sum('total') ?: 1;
                    $leaveColors = ['#34D399', '#F59E0B', '#6EA8FE', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'];
                @endphp

                <div class="d-flex flex-column gap-2">
                    @foreach ($leaveTypeStats->take(6) as $i => $lt)
                        @php $pct = round(($lt->total / $leaveTotal) * 100); @endphp
                        <div>
                            <div class="d-flex justify-content-between mb-1" style="font-size:12px;">
                                <span style="font-weight:600;color:var(--text-primary);">
                                    <span
                                        style="display:inline-block;width:7px;height:7px;border-radius:2px;
                                             background:{{ $leaveColors[$i % count($leaveColors)] }};margin-right:5px;"></span>
                                    {{ $lt->leavetype }}
                                </span>
                                <span style="color:var(--text-secondary);">{{ $lt->total }}
                                    ({{ $pct }}%)
                                </span>
                            </div>
                            <div style="height:5px;border-radius:99px;background:var(--border);overflow:hidden;">
                                <div
                                    style="height:100%;width:{{ $pct }}%;background:{{ $leaveColors[$i % count($leaveColors)] }};
                                        border-radius:99px;opacity:0.8;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($topLeaveRequester)
                    <div
                        style="margin-top:14px;padding:10px 12px;border-radius:10px;
                        background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.14);">
                        <div style="font-size:11px;color:var(--text-secondary);margin-bottom:2px;">Most leave requests
                        </div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-primary);">
                            {{ $topLeaveRequester->fullname }}</div>
                        <div style="font-size:11px;color:var(--text-secondary);">{{ $topLeaveRequester->total }} requests
                            this year</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Certificate Volume --}}
        <div class="col-lg-3">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">Certificates</div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Request volume</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(34,197,94,0.12);
                            display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-file-earmark-check" style="color:#22C55E;font-size:16px;"></i>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    @foreach ([['label' => 'Pending', 'key' => 'pending', 'color' => '#F59E0B', 'icon' => 'bi-hourglass-split'], ['label' => 'Accepted', 'key' => 'released', 'color' => '#22C55E', 'icon' => 'bi-check-circle'], ['label' => 'Declined', 'key' => 'rejected', 'color' => '#EF4444', 'icon' => 'bi-x-circle']] as $cs)
                        <div
                            style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;
                            background:rgba(0,0,0,0.02);border:1px solid var(--border);">
                            <i class="bi {{ $cs['icon'] }}"
                                style="color:{{ $cs['color'] }};font-size:15px;flex-shrink:0;"></i>
                            <span style="font-size:12.5px;color:var(--text-secondary);flex:1;">{{ $cs['label'] }}</span>
                            <span
                                style="font-size:15px;font-weight:700;color:var(--text-primary);">{{ $certStats[$cs['key']] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>

                <div
                    style="margin-top:12px;padding:10px 12px;border-radius:10px;
                        background:rgba(34,197,94,0.07);border:1px solid rgba(34,197,94,0.14);">
                    <div style="font-size:11px;color:var(--text-secondary);">This month's requests</div>
                    <div style="font-size:20px;font-weight:700;color:var(--text-primary);line-height:1.2;margin-top:2px;">
                        {{ $certStats['this_month'] }}
                        <span style="font-size:11px;font-weight:400;color:var(--text-secondary);">requests</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ROW 2: Headcount Trend + Retirement Watch --}}
    <div class="row g-3 mb-3 anim-fade-up" style="animation-delay:0.35s;">

        {{-- Headcount Sparkline --}}
        <div class="col-lg-5">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">Employee Headcount</div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Monthly growth · last 12
                            months</div>
                    </div>
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(52,211,153,0.12);
                            display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-graph-up" style="color:#059669;font-size:16px;"></i>
                    </div>
                </div>

                @php
                    $hcValues = $headcountTrend->pluck('count')->toArray();
                    $hcLabels = $headcountTrend->pluck('month')->toArray();
                    $hcMax = max(array_merge($hcValues, [1]));
                    $hcMin = min($hcValues);
                    $hcCount = count($hcValues);
                    $svgW = 380;
                    $svgH = 80;
                    $pad = 8;
                    $points = [];
                    foreach ($hcValues as $i => $v) {
                        $x = $pad + ($i / max($hcCount - 1, 1)) * ($svgW - $pad * 2);
                        $y = $svgH - $pad - (($v - $hcMin) / max($hcMax - $hcMin, 1)) * ($svgH - $pad * 2);
                        $points[] = "$x,$y";
                    }
                    $polyline = implode(' ', $points);
                    $firstPt = explode(',', $points[0]);
                    $lastPt = explode(',', end($points));
                    $areaPath =
                        "M {$firstPt[0]},{$firstPt[1]} " .
                        implode(' ', array_map(fn($p) => "L $p", $points)) .
                        " L {$lastPt[0]},{$svgH} L {$firstPt[0]},{$svgH} Z";
                @endphp

                <div style="margin:10px -4px 8px;">
                    <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}"
                        style="width:100%;height:80px;overflow:visible;">
                        <defs>
                            <linearGradient id="hcGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#34D399" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#34D399" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <path d="{{ $areaPath }}" fill="url(#hcGrad)" />
                        <polyline points="{{ $polyline }}" fill="none" stroke="#34D399" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        @foreach ($points as $i => $pt)
                            @php [$px,$py] = explode(',', $pt); @endphp
                            <circle cx="{{ $px }}" cy="{{ $py }}" r="3" fill="#34D399" />
                        @endforeach
                    </svg>
                </div>

                <div class="d-flex justify-content-between"
                    style="font-size:10px;color:var(--text-secondary);padding:0 4px;">
                    @foreach ($hcLabels as $lbl)
                        <span>{{ \Carbon\Carbon::parse($lbl . '-01')->format('M') }}</span>
                    @endforeach
                </div>

                <div style="margin-top:12px;display:flex;gap:12px;">
                    <div
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(52,211,153,0.07);
                            border:1px solid rgba(52,211,153,0.14);text-align:center;">
                        <div style="font-size:11px;color:var(--text-secondary);">Current</div>
                        <div style="font-size:20px;font-weight:700;color:var(--text-primary);">{{ $totalEmployees }}</div>
                    </div>
                    <div
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(34,197,94,0.07);
                            border:1px solid rgba(34,197,94,0.14);text-align:center;">
                        <div style="font-size:11px;color:var(--text-secondary);">Added (YTD)</div>
                        <div style="font-size:20px;font-weight:700;color:#22C55E;">+{{ $headcountYTD }}</div>
                    </div>
                    <div
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(239,68,68,0.07);
                            border:1px solid rgba(239,68,68,0.14);text-align:center;">
                        <div style="font-size:11px;color:var(--text-secondary);">Retired (YTD)</div>
                        <div style="font-size:20px;font-weight:700;color:#EF4444;">{{ $retiredYTD }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Retirement Watch --}}
        <div class="col-lg-7">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">
                            <i class="bi bi-shield-exclamation me-1" style="color:#F59E0B;"></i>
                            Retirement Watch
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                            Employees approaching retirement age (60) within 5 years
                        </div>
                    </div>
                    <span
                        style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;
                             background:rgba(245,158,11,0.12);color:#F59E0B;
                             border:1px solid rgba(245,158,11,0.25);">
                        {{ count($retirementWatch) }} flagged
                    </span>
                </div>

                @if (count($retirementWatch) > 0)
                    <div style="display:flex;flex-direction:column;gap:8px;max-height:280px;overflow-y:auto;">
                        @foreach ($retirementWatch as $emp)
                            @php
                                $yearsLeft = $emp->years_to_retire;
                                $urgency = $yearsLeft <= 1 ? 'danger' : ($yearsLeft <= 2 ? 'warning' : 'info');
                                $uc = [
                                    'danger' => [
                                        'bg' => 'rgba(239,68,68,0.08)',
                                        'border' => 'rgba(239,68,68,0.2)',
                                        'badge' => '#EF4444',
                                        'badgeBg' => 'rgba(239,68,68,0.12)',
                                    ],
                                    'warning' => [
                                        'bg' => 'rgba(245,158,11,0.08)',
                                        'border' => 'rgba(245,158,11,0.2)',
                                        'badge' => '#F59E0B',
                                        'badgeBg' => 'rgba(245,158,11,0.12)',
                                    ],
                                    'info' => [
                                        'bg' => 'rgba(52,211,153,0.05)',
                                        'border' => 'rgba(52,211,153,0.15)',
                                        'badge' => '#059669',
                                        'badgeBg' => 'rgba(52,211,153,0.1)',
                                    ],
                                ][$urgency];
                            @endphp
                            <div
                                style="display:flex;align-items:center;gap:12px;padding:10px 14px;
                                border-radius:10px;background:{{ $uc['bg'] }};border:1px solid {{ $uc['border'] }};">
                                <div
                                    style="width:36px;height:36px;border-radius:10px;flex-shrink:0;
                                    background:{{ $uc['badgeBg'] }};border:1px solid {{ $uc['border'] }};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:12px;font-weight:700;color:{{ $uc['badge'] }};">
                                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div
                                        style="font-size:13px;font-weight:700;color:var(--text-primary);
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $emp->full_name }}
                                    </div>
                                    <div style="font-size:11.5px;color:var(--text-secondary);">
                                        {{ $emp->position ?? '—' }}
                                        &nbsp;·&nbsp; Age {{ $emp->current_age }}
                                        &nbsp;·&nbsp; {{ $emp->years_of_service }} yrs service
                                    </div>
                                </div>
                                <div style="text-align:right;flex-shrink:0;">
                                    <div
                                        style="font-size:11px;font-weight:700;color:{{ $uc['badge'] }};
                                        padding:3px 8px;border-radius:99px;
                                        background:{{ $uc['badgeBg'] }};border:1px solid {{ $uc['border'] }};
                                        white-space:nowrap;">
                                        @if ($yearsLeft <= 0)
                                            Retirement due
                                        @elseif($yearsLeft < 1)
                                            &lt; 1 yr left
                                        @else
                                            {{ $yearsLeft }} yr{{ $yearsLeft != 1 ? 's' : '' }} left
                                        @endif
                                    </div>
                                    <div style="font-size:10.5px;color:var(--text-secondary);margin-top:3px;">
                                        Born {{ \Carbon\Carbon::parse($emp->birthdate)->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;padding:2rem 0;color:var(--text-secondary);">
                        <i class="bi bi-check-circle"
                            style="font-size:28px;color:#22C55E;display:block;margin-bottom:8px;"></i>
                        <div style="font-size:13px;">No employees approaching retirement in the next 5 years.</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection

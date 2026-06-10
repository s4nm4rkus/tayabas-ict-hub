@extends('layouts.hr')
@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')

    {{-- Flash --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
            style="border-radius:var(--radius-sm);border:none;
                background:rgba(34,197,94,0.1);border-left:3px solid #22C55E;
                color:#166534;font-size:13px;padding:10px 14px;margin-bottom:16px;">
            <i class="bi bi-check-circle-fill me-2" style="color:#22C55E;"></i>
            {{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
     TOP BAR — page title left | tab nav right
═══════════════════════════════════════════════════════ --}}
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
    <div class="anim-fade-up"
        style="display:flex;align-items:center;justify-content:space-between;
            flex-wrap:wrap;gap:12px;margin-bottom:18px;">
        {{-- Horizontal tab bar --}}
        <div
            style="display:flex;align-items:center;gap:4px;
                background:var(--bg);border:1px solid var(--border);
                border-radius:var(--radius-sm);padding:4px;">
            @foreach ([['tools', 'bi-fingerprint', 'Import / Export', 'rgba(110,168,254,0.15)', '#4A90E2'], ['summary', 'bi-bar-chart-line', 'Monthly Summary', 'rgba(110,168,254,0.15)', '#4A90E2'], ['danger', 'bi-trash', 'Reset Data', 'rgba(239,68,68,0.12)', '#DC2626']] as [$tab, $icon, $label, $activeBg, $activeClr])
                <button onclick="switchAttTab('{{ $tab }}')" id="atttab-{{ $tab }}"
                    style="display:flex;align-items:center;gap:7px;padding:7px 14px;
                   border-radius:6px;font-size:12px;font-weight:600;
                   border:none;cursor:pointer;transition:all 0.15s;white-space:nowrap;
                   background:{{ $tab === 'tools' ? $activeBg : 'transparent' }};
                   color:{{ $tab === 'tools' ? $activeClr : 'var(--text-secondary)' }};"
                    data-activebg="{{ $activeBg }}" data-activeclr="{{ $activeClr }}">
                    <i class="bi {{ $icon }}" style="font-size:12px;"></i>
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
     PANEL: TOOLS (Import/Export) — shown by default
═══════════════════════════════════════════════════════════════════════ --}}
    <div id="attpanel-tools" class="anim-fade-up">

        {{-- 3-column layout: tools | employee list | detail --}}
        <div style="display:grid;grid-template-columns: 210px 1fr 190px;gap:14px;align-items:start;">



            {{-- ── Employee List ── --}}
            <div class="stat-card" style="padding:0;overflow:hidden;">
                <div style="padding:12px 14px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div
                                style="width:24px;height:24px;border-radius:6px;
                                    background:rgba(139,92,246,0.12);
                                    display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-people" style="color:#8B5CF6;font-size:11px;"></i>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:var(--text-primary);">Employees</span>
                        </div>
                        <span
                            style="font-size:10px;padding:2px 8px;border-radius:99px;
                                 background:var(--bg);border:1px solid var(--border);
                                 color:var(--text-secondary);font-weight:600;">
                            {{ $employeesWithRecords->count() }}
                        </span>
                    </div>

                    {{-- Month picker --}}
                    <form method="GET" action="{{ route('hr.attendance.index') }}" id="monthForm">
                        @if (request('employee_id'))
                            <input type="hidden" name="employee_id" value="{{ request('employee_id') }}">
                        @endif
                        <input type="month" name="month" class="form-control form-control-sm"
                            style="font-size:11px;margin-bottom:8px;" value="{{ $month }}"
                            onchange="document.getElementById('monthForm').submit()">
                    </form>

                    {{-- Search --}}
                    <div style="position:relative;">
                        <i class="bi bi-search"
                            style="position:absolute;left:8px;top:50%;transform:translateY(-50%);
                              font-size:10px;color:var(--text-secondary);pointer-events:none;"></i>
                        <input type="text" id="empSearch" placeholder="Search employee…"
                            oninput="filterEmployees(this.value)"
                            style="width:100%;padding:6px 8px 6px 26px;font-size:11px;
                               border:1px solid var(--border);border-radius:var(--radius-sm);
                               background:var(--bg);color:var(--text-primary);
                               outline:none;transition:border-color 0.15s;"
                            onfocus="this.style.borderColor='#4A90E2'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>

                {{-- Sort pills --}}
                <div
                    style="padding:6px 14px;border-bottom:1px solid var(--border);
                        display:flex;align-items:center;gap:4px;background:var(--bg);">
                    <span
                        style="font-size:9px;font-weight:700;color:var(--text-secondary);
                             text-transform:uppercase;letter-spacing:0.06em;margin-right:2px;">Sort</span>
                    @foreach ([['name', 'Name'], ['days', 'Days'], ['late', 'Late']] as [$v, $l])
                        <button onclick="sortEmployees('{{ $v }}')" id="sort-{{ $v }}"
                            style="font-size:10px;padding:3px 9px;border-radius:99px;cursor:pointer;
                           transition:all 0.15s;
                           border:1px solid {{ $v === 'name' ? '#4A90E2' : 'var(--border)' }};
                           background:{{ $v === 'name' ? 'rgba(110,168,254,0.12)' : 'transparent' }};
                           color:{{ $v === 'name' ? '#4A90E2' : 'var(--text-secondary)' }};
                           font-weight:{{ $v === 'name' ? '600' : 'normal' }};">
                            {{ $l }}
                        </button>
                    @endforeach
                </div>

                {{-- List --}}
                <div id="empListScroll" style="max-height:calc(100vh - 380px);min-height:180px;overflow-y:auto;"
                    onscroll="sessionStorage.setItem('empListScroll', this.scrollTop)">
                    @php
                        $avatarPalette = [
                            ['bg' => 'rgba(110,168,254,0.18)', 'color' => '#2563EB'],
                            ['bg' => 'rgba(139,92,246,0.18)', 'color' => '#7C3AED'],
                            ['bg' => 'rgba(52,211,153,0.18)', 'color' => '#059669'],
                            ['bg' => 'rgba(245,158,11,0.18)', 'color' => '#B45309'],
                            ['bg' => 'rgba(239,68,68,0.18)', 'color' => '#DC2626'],
                            ['bg' => 'rgba(29,158,117,0.18)', 'color' => '#0F6E56'],
                        ];
                    @endphp
                    @forelse($employeesWithRecords as $emp)
                        @php
                            $empRecs = $byEmployee[$emp->id] ?? collect();
                            $hasLate = $empRecs->sum('late_minutes') > 0;
                            $hasUT = $empRecs->sum('undertime_minutes') > 0;
                            $dot = $hasLate ? '#F59E0B' : ($hasUT ? '#EF4444' : '#22C55E');
                            $isActive = $selectedEmployee?->id == $emp->id;
                            $parts = explode(',', $emp->full_name);
                            $initials = strtoupper(
                                substr(trim($parts[0] ?? ''), 0, 1) . substr(trim($parts[1] ?? ''), 0, 1),
                            );
                            $ac = $avatarPalette[$emp->id % count($avatarPalette)];
                        @endphp
                        <a href="{{ route('hr.attendance.index', ['employee_id' => $emp->id, 'month' => $month]) }}"
                            class="emp-list-item" data-name="{{ strtolower($emp->full_name) }}"
                            data-days="{{ $empRecs->count() }}" data-late="{{ $empRecs->sum('late_minutes') }}"
                            data-hours="{{ $empRecs->sum('total_hours') }}"
                            style="display:flex;align-items:center;gap:9px;padding:9px 14px;
                              text-decoration:none;border-bottom:1px solid var(--border);
                              transition:background 0.12s;
                              background:{{ $isActive ? 'rgba(110,168,254,0.08)' : 'transparent' }};
                              border-left:3px solid {{ $isActive ? '#4A90E2' : 'transparent' }};">
                            <div
                                style="width:28px;height:28px;border-radius:50%;flex-shrink:0;
                                    background:{{ $ac['bg'] }};color:{{ $ac['color'] }};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:10px;font-weight:800;">
                                {{ $initials }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:11px;font-weight:600;color:var(--text-primary);
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $emp->full_name }}
                                </div>
                                <div style="font-size:10px;color:var(--text-secondary);margin-top:1px;">
                                    {{ $empRecs->count() }}d &bull;
                                    {{ number_format($empRecs->sum('total_hours'), 1) }}h
                                </div>
                            </div>
                            <span
                                style="width:7px;height:7px;border-radius:50%;flex-shrink:0;
                                     background:{{ $dot }};box-shadow:0 0 5px {{ $dot }}66;"></span>
                        </a>
                    @empty
                        <div style="padding:3rem 1rem;text-align:center;color:var(--text-secondary);">
                            <i class="bi bi-calendar-x"
                                style="font-size:28px;display:block;margin-bottom:8px;opacity:0.25;"></i>
                            <div style="font-size:12px;font-weight:600;margin-bottom:4px;">No records</div>
                            <div style="font-size:11px;margin-bottom:14px;">
                                {{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}
                            </div>
                            <a href="{{ route('hr.zkteco.upload') }}" class="btn btn-primary btn-sm"
                                style="font-size:11px;">
                                <i class="bi bi-upload me-1"></i>Import
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ── Detail Panel ── --}}
            <div>
                @if ($selectedEmployee)
                    @php
                        $totalHours = $selectedRecords->sum('total_hours');
                        $totalLate = $selectedRecords->sum('late_minutes');
                        $totalUT = $selectedRecords->sum('undertime_minutes');
                        $daysCount = $selectedRecords->count();
                        $points = round((0.42 / 8) * $totalHours, 4);
                        $parts2 = explode(',', $selectedEmployee->full_name);
                        $initials2 = strtoupper(
                            substr(trim($parts2[0] ?? ''), 0, 1) . substr(trim($parts2[1] ?? ''), 0, 1),
                        );
                        $ac2 = $avatarPalette[$selectedEmployee->id % count($avatarPalette)];
                        [$y2, $m2] = explode('-', $month);
                        $monthLabel = \Carbon\Carbon::create($y2, $m2, 1)->format('F Y');
                    @endphp
                    <div class="stat-card" style="padding:0;overflow:hidden;">

                        {{-- Header --}}
                        <div
                            style="padding:14px 18px;border-bottom:1px solid var(--border);
                                display:flex;align-items:center;justify-content:space-between;
                                flex-wrap:wrap;gap:10px;">
                            <div style="display:flex;align-items:center;gap:11px;">
                                <div
                                    style="width:40px;height:40px;border-radius:50%;flex-shrink:0;
                                        background:{{ $ac2['bg'] }};color:{{ $ac2['color'] }};
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:14px;font-weight:800;">
                                    {{ $initials2 }}</div>
                                <div>
                                    <div
                                        style="font-size:14px;font-weight:800;color:var(--text-primary);
                                            letter-spacing:-0.2px;">
                                        {{ $selectedEmployee->full_name }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">
                                        <strong style="color:var(--text-primary);">
                                            {{ $selectedEmployee->user?->user_pos ?? 'N/A' }}
                                        </strong>
                                        <span style="margin:0 5px;opacity:0.35;">|</span>
                                        {{ $monthLabel }}
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                <a href="{{ route('hr.zkteco.dtr', ['employee_id' => $selectedEmployee->id, 'month' => $month]) }}"
                                    target="_blank"
                                    style="display:flex;align-items:center;gap:6px;padding:7px 15px;
                                      border-radius:var(--radius-sm);font-size:12px;font-weight:700;
                                      background:linear-gradient(135deg,#6EA8FE,#4A90E2);color:white;
                                      text-decoration:none;box-shadow:0 2px 8px rgba(74,144,226,0.3);
                                      transition:opacity 0.15s;"
                                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                    <i class="bi bi-printer" style="font-size:12px;"></i> Print DTR
                                </a>
                                <form method="POST" action="{{ route('hr.attendance.reset.employee') }}"
                                    onsubmit="return confirmReset('Delete ALL attendance for {{ addslashes($selectedEmployee->full_name) }} — {{ $monthLabel }}?\n\nAttendance, logs & points.\n\nCannot be undone.')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <button type="submit"
                                        style="display:flex;align-items:center;gap:6px;padding:7px 13px;
                                           border-radius:var(--radius-sm);font-size:12px;font-weight:600;
                                           background:rgba(239,68,68,0.07);color:#DC2626;cursor:pointer;
                                           border:1.5px solid rgba(239,68,68,0.2);transition:all 0.15s;"
                                        onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                                        onmouseout="this.style.background='rgba(239,68,68,0.07)'">
                                        <i class="bi bi-person-x" style="font-size:12px;"></i> Clear
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- 4 stat boxes --}}
                        <div
                            style="display:grid;grid-template-columns:repeat(4,1fr);
                                border-bottom:1px solid var(--border);">
                            @foreach ([['Days', $daysCount, '#22C55E'], ['Hours', number_format($totalHours, 1) . 'h', '#4A90E2'], ['Late', $totalLate > 0 ? floor($totalLate / 60) . 'h ' . $totalLate % 60 . 'm' : '—', $totalLate > 0 ? '#F59E0B' : 'var(--text-secondary)'], ['Undertime', $totalUT > 0 ? floor($totalUT / 60) . 'h ' . $totalUT % 60 . 'm' : '—', $totalUT > 0 ? '#EF4444' : 'var(--text-secondary)']] as $idx => [$lbl, $val, $clr])
                                <div
                                    style="padding:14px 10px;text-align:center;
                                    {{ $idx < 3 ? 'border-right:1px solid var(--border);' : '' }}">
                                    <div
                                        style="font-size:17px;font-weight:800;color:{{ $clr }};
                                        letter-spacing:-0.4px;">
                                        {{ $val }}</div>
                                    <div
                                        style="font-size:10px;font-weight:600;color:var(--text-secondary);
                                        text-transform:uppercase;letter-spacing:0.06em;margin-top:3px;">
                                        {{ $lbl }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Filter bar --}}
                        <div
                            style="padding:9px 16px;border-bottom:1px solid var(--border);
                                display:flex;gap:8px;align-items:center;flex-wrap:wrap;
                                background:var(--bg);">
                            <form method="GET" action="{{ route('hr.attendance.index') }}"
                                style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin:0;">
                                <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                                <input type="hidden" name="month" value="{{ $month }}">
                                <select name="status" class="form-select form-select-sm"
                                    style="font-size:11px;max-width:140px;">
                                    <option value="">All days</option>
                                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late only
                                    </option>
                                    <option value="undertime" {{ request('status') === 'undertime' ? 'selected' : '' }}>
                                        Undertime only</option>
                                    <option value="complete" {{ request('status') === 'complete' ? 'selected' : '' }}>
                                        Complete
                                        only</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary btn-sm" style="font-size:11px;">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                @if (request('status'))
                                    <a href="{{ route('hr.attendance.index', ['employee_id' => $selectedEmployee->id, 'month' => $month]) }}"
                                        class="btn btn-outline-secondary btn-sm" style="font-size:11px;">
                                        <i class="bi bi-x me-1"></i>Clear
                                    </a>
                                @endif
                            </form>
                            <span style="font-size:11px;color:var(--text-secondary);margin-left:auto;font-weight:500;">
                                {{ $selectedRecords->count() }} record(s)
                            </span>
                        </div>

                        {{-- Records table --}}
                        <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                            <table class="table mb-0" style="font-size:12px;">
                                <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                                    <tr>
                                        @foreach (['Date', 'AM In', 'AM Out', 'PM In', 'PM Out', 'Hours', 'Late', 'Undertime', 'Pts', ''] as $th)
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
                                    @forelse($selectedRecords as $att)
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
                                        @endphp
                                        <tr style="background:{{ $rowBg }};">
                                            <td style="padding:8px;vertical-align:middle;white-space:nowrap;">
                                                <div style="font-size:11px;font-weight:700;color:var(--text-primary);">
                                                    {{ $att->t_date?->format('D') }}
                                                </div>
                                                <div style="font-size:10px;color:var(--text-secondary);">
                                                    {{ $att->t_date?->format('M d') }}
                                                </div>
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($att->am_time_in)
                                                    <span
                                                        style="font-size:10px;font-weight:600;padding:2px 5px;
                                                             border-radius:4px;white-space:nowrap;
                                                             background:rgba(110,168,254,0.12);color:#2563EB;">
                                                        {{ $fmt($att->am_time_in) }}</span>
                                                @else
                                                    <span style="color:var(--text-secondary);">—</span>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($att->am_time_out)
                                                    <span
                                                        style="font-size:10px;padding:2px 5px;border-radius:4px;
                                                             white-space:nowrap;
                                                             background:rgba(110,168,254,0.07);color:#2563EB;">
                                                        {{ $fmt($att->am_time_out) }}</span>
                                                @else
                                                    <span style="color:var(--text-secondary);">—</span>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($att->pm_time_in)
                                                    <span
                                                        style="font-size:10px;font-weight:600;padding:2px 5px;
                                                             border-radius:4px;white-space:nowrap;
                                                             background:rgba(245,158,11,0.12);color:#B45309;">
                                                        {{ $fmt($att->pm_time_in) }}</span>
                                                @else
                                                    <span style="color:var(--text-secondary);">—</span>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($att->pm_time_out)
                                                    <span
                                                        style="font-size:10px;padding:2px 5px;border-radius:4px;
                                                             white-space:nowrap;
                                                             background:rgba(245,158,11,0.07);color:#B45309;">
                                                        {{ $fmt($att->pm_time_out) }}</span>
                                                @else
                                                    <span style="color:var(--text-secondary);">—</span>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                <span class="status-badge badge-info" style="font-size:10px;">
                                                    {{ number_format((float) $att->total_hours, 1) }}h
                                                </span>
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($isLate)
                                                    @php
                                                        $lh = intdiv($att->late_minutes, 60);
                                                        $lm = $att->late_minutes % 60;
                                                    @endphp
                                                    <span
                                                        style="font-size:10px;font-weight:600;padding:2px 5px;
                                                             border-radius:4px;white-space:nowrap;
                                                             background:rgba(245,158,11,0.12);color:#B45309;">
                                                        {{ $lh > 0 ? $lh . 'h ' : '' }}{{ $lm }}m</span>
                                                @else
                                                    <i class="bi bi-check-circle-fill"
                                                        style="font-size:12px;color:#22C55E;"></i>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                @if ($isUT)
                                                    @php
                                                        $uh = intdiv($att->undertime_minutes, 60);
                                                        $um = $att->undertime_minutes % 60;
                                                    @endphp
                                                    <span
                                                        style="font-size:10px;font-weight:600;padding:2px 5px;
                                                             border-radius:4px;white-space:nowrap;
                                                             background:rgba(239,68,68,0.12);color:#DC2626;">
                                                        {{ $uh > 0 ? $uh . 'h ' : '' }}{{ $um }}m</span>
                                                @else
                                                    <i class="bi bi-check-circle-fill"
                                                        style="font-size:12px;color:#22C55E;"></i>
                                                @endif
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                <span style="font-size:10px;font-weight:700;color:#059669;">
                                                    {{ number_format(round((0.42 / 8) * (float) $att->total_hours, 4), 4) }}
                                                </span>
                                            </td>
                                            <td style="padding:8px 6px;vertical-align:middle;">
                                                <form method="POST"
                                                    action="{{ route('hr.attendance.destroy', $att->id) }}"
                                                    onsubmit="return confirm('Delete this record?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        style="width:26px;height:26px;border-radius:6px;
                                                           background:rgba(239,68,68,0.07);color:#B91C1C;
                                                           border:1px solid rgba(239,68,68,0.15);
                                                           display:flex;align-items:center;justify-content:center;
                                                           cursor:pointer;padding:0;transition:all 0.15s;"
                                                        onmouseover="this.style.background='rgba(239,68,68,0.18)'"
                                                        onmouseout="this.style.background='rgba(239,68,68,0.07)'">
                                                        <i class="bi bi-trash" style="font-size:10px;"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10"
                                                style="text-align:center;padding:4rem;color:var(--text-secondary);">
                                                <i class="bi bi-calendar-x"
                                                    style="font-size:28px;display:block;margin-bottom:10px;opacity:0.25;"></i>
                                                <div style="font-size:12px;font-weight:600;">
                                                    No records match the current filter.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer --}}
                        @if ($selectedRecords->count() > 0)
                            <div
                                style="padding:10px 16px;border-top:1px solid var(--border);background:var(--bg);
                                display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                <div style="display:flex;align-items:center;gap:14px;font-size:11px;">
                                    <span style="color:var(--text-secondary);">
                                        <strong
                                            style="color:var(--text-primary);">{{ $selectedRecords->count() }}</strong>
                                        day(s)
                                    </span>
                                    <span><strong
                                            style="color:#4A90E2;">{{ number_format($totalHours, 2) }}h</strong></span>
                                    <span><strong style="color:#059669;">{{ $points }} pts</strong></span>
                                </div>
                                <a href="{{ route('hr.attendance.export.csv', ['employee_id' => $selectedEmployee->id, 'month' => $month]) }}"
                                    style="font-size:11px;font-weight:600;color:#4A90E2;text-decoration:none;
                                  display:flex;align-items:center;gap:5px;padding:5px 10px;
                                  border-radius:var(--radius-sm);border:1px solid rgba(110,168,254,0.3);
                                  background:rgba(110,168,254,0.06);transition:all 0.15s;"
                                    onmouseover="this.style.background='rgba(110,168,254,0.13)'"
                                    onmouseout="this.style.background='rgba(110,168,254,0.06)'">
                                    <i class="bi bi-download" style="font-size:11px;"></i> Export CSV
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="stat-card"
                        style="display:flex;flex-direction:column;align-items:center;
                            justify-content:center;padding:6rem 2rem;text-align:center;min-height:400px;">
                        <div
                            style="width:60px;height:60px;border-radius:16px;background:rgba(139,92,246,0.08);
                                display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="bi bi-person-lines-fill" style="font-size:26px;color:#8B5CF6;opacity:0.45;"></i>
                        </div>
                        <div style="font-size:14px;font-weight:800;color:var(--text-primary);margin-bottom:6px;">
                            Select an employee
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);max-width:260px;line-height:1.75;">
                            Pick a name from the list to view their monthly attendance and print their DTR.
                        </div>
                        @if ($employeesWithRecords->isEmpty())
                            <a href="{{ route('hr.zkteco.upload') }}" class="btn btn-primary btn-sm mt-4"
                                style="font-size:12px;">
                                <i class="bi bi-fingerprint me-1"></i> Import ZKTeco Logs
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            {{-- ── Tool Actions + Legend ── --}}
            <div style="display:flex;flex-direction:column;gap:8px;">

                <a href="{{ route('hr.zkteco.upload') }}" class="att-tool-link"
                    style="display:flex;align-items:center;gap:10px;padding:11px 13px;
                      border-radius:var(--radius-sm);text-decoration:none;
                      background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.18);
                      transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(52,211,153,0.14)'"
                    onmouseout="this.style.background='rgba(52,211,153,0.06)'">
                    <div
                        style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                            background:linear-gradient(135deg,#34D399,#059669);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 2px 8px rgba(5,150,105,0.2);">
                        <i class="bi bi-fingerprint" style="font-size:14px;color:white;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-primary);">Import ZKTeco</div>
                        <div style="font-size:10px;color:var(--text-secondary);margin-top:1px;">.txt / .dat file</div>
                    </div>
                </a>

                <a href="{{ route('hr.attendance.export.csv', ['month' => $month]) }}"
                    style="display:flex;align-items:center;gap:10px;padding:11px 13px;
                      border-radius:var(--radius-sm);text-decoration:none;
                      background:rgba(110,168,254,0.06);border:1px solid rgba(110,168,254,0.18);
                      transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(110,168,254,0.14)'"
                    onmouseout="this.style.background='rgba(110,168,254,0.06)'">
                    <div
                        style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                            background:linear-gradient(135deg,#6EA8FE,#4A90E2);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 2px 8px rgba(74,144,226,0.2);">
                        <i class="bi bi-download" style="font-size:14px;color:white;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-primary);">Export CSV</div>
                        <div style="font-size:10px;color:var(--text-secondary);margin-top:1px;">
                            {{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}
                        </div>
                    </div>
                </a>

                <a href="{{ route('hr.zkteco.history') }}"
                    style="display:flex;align-items:center;gap:10px;padding:11px 13px;
                      border-radius:var(--radius-sm);text-decoration:none;
                      background:rgba(139,92,246,0.06);border:1px solid rgba(139,92,246,0.18);
                      transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(139,92,246,0.14)'"
                    onmouseout="this.style.background='rgba(139,92,246,0.06)'">
                    <div
                        style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                            background:linear-gradient(135deg,#A78BFA,#7C3AED);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 2px 8px rgba(124,58,237,0.2);">
                        <i class="bi bi-clock-history" style="font-size:14px;color:white;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--text-primary);">Import History</div>
                        <div style="font-size:10px;color:var(--text-secondary);margin-top:1px;">Past uploads</div>
                    </div>
                </a>

                {{-- Status Legend --}}
                <div class="stat-card" style="padding:12px 14px;margin-top:4px;">
                    <div
                        style="font-size:10px;font-weight:700;color:var(--text-secondary);
                            text-transform:uppercase;letter-spacing:0.07em;margin-bottom:10px;">
                        Status Legend
                    </div>
                    @foreach ([['#22C55E', 'On Time', 'In by 9:00 AM'], ['#F59E0B', 'Late', 'After 9:00 AM'], ['#EF4444', 'Undertime', 'Under 8 hours']] as [$c, $s, $d])
                        <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px;">
                            <span
                                style="width:7px;height:7px;border-radius:50%;background:{{ $c }};
                                 flex-shrink:0;margin-top:3px;box-shadow:0 0 5px {{ $c }}66;"></span>
                            <div>
                                <div style="font-size:11px;font-weight:600;color:var(--text-primary);">{{ $s }}
                                </div>
                                <div style="font-size:10px;color:var(--text-secondary);">{{ $d }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>{{-- /attpanel-tools --}}


    {{-- ═══════════════════════════════════════════════════════════════════════
     PANEL: MONTHLY SUMMARY — full-width, clean table layout
═══════════════════════════════════════════════════════════════════════ --}}
    <div id="attpanel-summary" style="display:none;" class="anim-fade-up">
        @php
            $grandDays = $employeesWithRecords->sum(fn($e) => ($byEmployee[$e->id] ?? collect())->count());
            $grandHours = $employeesWithRecords->sum(fn($e) => ($byEmployee[$e->id] ?? collect())->sum('total_hours'));
            $grandLate = $employeesWithRecords->sum(fn($e) => ($byEmployee[$e->id] ?? collect())->sum('late_minutes'));
            $grandUT = $employeesWithRecords->sum(
                fn($e) => ($byEmployee[$e->id] ?? collect())->sum('undertime_minutes'),
            );
            $grandPts = round((0.42 / 8) * $grandHours, 2);
        @endphp



        {{-- Summary table card --}}
        <div class="stat-card" style="padding:0;overflow:hidden;">

            {{-- Table header bar --}}
            <div
                style="padding:13px 18px;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:26px;height:26px;border-radius:7px;background:rgba(110,168,254,0.12);
                            display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-table" style="color:#4A90E2;font-size:11px;"></i>
                    </div>
                    <div>
                        <span style="font-size:13px;font-weight:700;color:var(--text-primary);">
                            Employee Breakdown
                        </span>
                        <span style="font-size:11px;color:var(--text-secondary);margin-left:8px;">
                            {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                        </span>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="font-size:11px;color:var(--text-secondary);margin:0;">Sort by</label>
                    <select id="summarySort" onchange="renderSummary()"
                        style="font-size:11px;padding:5px 10px;border:1px solid var(--border);
                           border-radius:var(--radius-sm);background:var(--bg);
                           color:var(--text-primary);outline:none;">
                        <option value="name">Name A–Z</option>
                        <option value="days">Days ↓</option>
                        <option value="hours">Hours ↓</option>
                        <option value="late">Late ↓</option>
                        <option value="undertime">Undertime ↓</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:var(--bg);">
                            <th
                                style="padding:10px 18px;text-align:left;font-size:10px;font-weight:700;
                                   color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;width:32px;">
                                #
                            </th>
                            <th
                                style="padding:10px 18px;text-align:left;font-size:10px;font-weight:700;
                                   color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);">
                                Employee
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:#059669;text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Days Present
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:#2563EB;text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Total Hours
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:#B45309;text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Late
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:#DC2626;text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Undertime
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:#059669;text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Points
                            </th>
                            <th
                                style="padding:10px 18px;text-align:center;font-size:10px;font-weight:700;
                                   color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;
                                   border-bottom:1.5px solid var(--border);white-space:nowrap;">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody id="summaryTbody"></tbody>
                </table>
                @if ($employeesWithRecords->isEmpty())
                    <div style="padding:4rem;text-align:center;color:var(--text-secondary);">
                        <i class="bi bi-calendar-x"
                            style="font-size:32px;display:block;margin-bottom:10px;opacity:0.2;"></i>
                        <div style="font-size:13px;font-weight:600;">No records for this month.</div>
                    </div>
                @endif
            </div>


        </div>
    </div>{{-- /attpanel-summary --}}


    {{-- ═══════════════════════════════════════════════════════════════════════
     PANEL: RESET / DANGER
═══════════════════════════════════════════════════════════════════════ --}}
    <div id="attpanel-danger" style="display:none;" class="anim-fade-up">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:680px;">

            {{-- Warning --}}
            <div
                style="grid-column:1/-1;background:rgba(239,68,68,0.06);
                    border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-sm);
                    padding:11px 14px;font-size:12px;color:#B91C1C;line-height:1.6;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Permanently deletes records. <strong>Cannot be undone.</strong>
                Export a CSV backup first before proceeding.
            </div>

            {{-- Clear whole month --}}
            <div class="stat-card" style="padding:16px;">
                <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
                    <div
                        style="width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,0.08);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-calendar-x" style="color:#DC2626;font-size:14px;"></i>
                    </div>
                    <div style="font-size:12px;font-weight:700;color:var(--text-primary);">
                        Clear Entire Month
                    </div>
                </div>
                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:14px;line-height:1.6;">
                    All records, punch logs &amp; points for
                    <strong style="color:var(--text-primary);">
                        {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </strong>.
                </div>
                <form method="POST" action="{{ route('hr.attendance.reset.month') }}"
                    onsubmit="return confirmReset('Delete ALL data for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}?\n\nAttendance + punch logs + points.\n\nCannot be undone.')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit"
                        style="width:100%;padding:9px;border-radius:var(--radius-sm);font-size:12px;
                           font-weight:600;background:rgba(239,68,68,0.08);color:#DC2626;
                           border:1.5px solid rgba(239,68,68,0.25);cursor:pointer;
                           display:flex;align-items:center;justify-content:center;gap:7px;
                           transition:all 0.15s;"
                        onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                        onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                        <i class="bi bi-calendar-x"></i>
                        Clear {{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}
                    </button>
                </form>
            </div>

            {{-- Clear one employee --}}
            <div class="stat-card" style="padding:16px;">
                <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
                    <div
                        style="width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,0.08);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person-x" style="color:#DC2626;font-size:14px;"></i>
                    </div>
                    <div style="font-size:12px;font-weight:700;color:var(--text-primary);">
                        Clear One Employee
                    </div>
                </div>
                <div style="font-size:11px;color:var(--text-secondary);margin-bottom:14px;line-height:1.6;">
                    Removes their attendance, logs &amp; points for this month.
                </div>
                <form method="POST" action="{{ route('hr.attendance.reset.employee') }}"
                    onsubmit="return confirmReset('Clear selected employee data for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}?\n\nCannot be undone.')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="month" value="{{ $month }}">
                    <select name="employee_id" class="form-select form-select-sm"
                        style="font-size:11px;margin-bottom:10px;" required>
                        <option value="">Select employee…</option>
                        @foreach ($employeesWithRecords as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        style="width:100%;padding:9px;border-radius:var(--radius-sm);font-size:12px;
                           font-weight:600;background:rgba(239,68,68,0.08);color:#DC2626;
                           border:1.5px solid rgba(239,68,68,0.25);cursor:pointer;
                           display:flex;align-items:center;justify-content:center;gap:7px;
                           transition:all 0.15s;"
                        onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                        onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                        <i class="bi bi-person-x"></i>
                        Clear Employee
                    </button>
                </form>
            </div>

        </div>
    </div>{{-- /attpanel-danger --}}


    @push('scripts')
        <script>
            // ── Tab switching ───────────────────────────────────────────────────────
            function switchAttTab(tab) {
                ['tools', 'summary', 'danger'].forEach(t => {
                    document.getElementById('attpanel-' + t).style.display = 'none';
                    const btn = document.getElementById('atttab-' + t);
                    btn.style.background = 'transparent';
                    btn.style.color = 'var(--text-secondary)';
                });

                document.getElementById('attpanel-' + tab).style.display = 'block';

                const btn = document.getElementById('atttab-' + tab);
                btn.style.background = btn.dataset.activebg;
                btn.style.color = btn.dataset.activeclr;

                if (tab === 'summary') renderSummary();
            }

            // ── Two-step reset confirmation ─────────────────────────────────────────
            function confirmReset(message) {
                if (!confirm(message)) return false;
                return prompt('Type DELETE to confirm:') === 'DELETE';
            }

            // ── Restore scroll position ─────────────────────────────────────────────
            (function() {
                const el = document.getElementById('empListScroll');
                const saved = sessionStorage.getItem('empListScroll');
                if (el && saved) el.scrollTop = parseInt(saved, 10);
            })();

            // ── Employee search ─────────────────────────────────────────────────────
            function filterEmployees(q) {
                q = q.toLowerCase();
                document.querySelectorAll('.emp-list-item').forEach(el => {
                    el.style.display = el.dataset.name.includes(q) ? 'flex' : 'none';
                });
            }

            // ── Employee sort ───────────────────────────────────────────────────────
            function sortEmployees(by) {
                ['name', 'days', 'late'].forEach(k => {
                    const b = document.getElementById('sort-' + k);
                    const on = k === by;
                    b.style.background = on ? 'rgba(110,168,254,0.12)' : 'transparent';
                    b.style.color = on ? '#4A90E2' : 'var(--text-secondary)';
                    b.style.borderColor = on ? '#4A90E2' : 'var(--border)';
                    b.style.fontWeight = on ? '600' : 'normal';
                });
                const wrap = document.getElementById('empListScroll');
                const items = [...wrap.querySelectorAll('.emp-list-item')];
                items.sort((a, b) => {
                    if (by === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                    if (by === 'days') return parseFloat(b.dataset.days) - parseFloat(a.dataset.days);
                    if (by === 'late') return parseFloat(b.dataset.late) - parseFloat(a.dataset.late);
                    return 0;
                });
                items.forEach(el => wrap.appendChild(el));
            }

            // ── Summary data + helpers ──────────────────────────────────────────────
            const summaryData = {!! json_encode(
                $employeesWithRecords->map(function ($emp) use ($byEmployee) {
                        $recs = $byEmployee[$emp->id] ?? collect();
                        return [
                            'name' => $emp->full_name,
                            'days' => $recs->count(),
                            'hours' => round((float) $recs->sum('total_hours'), 2),
                            'late' => (int) $recs->sum('late_minutes'),
                            'undertime' => (int) $recs->sum('undertime_minutes'),
                        ];
                    })->values(),
            ) !!};

            function fmtMin(m) {
                if (!m) return '<span style="color:var(--text-secondary);">—</span>';
                const h = Math.floor(m / 60),
                    r = m % 60;
                return h > 0 ? (r > 0 ? h + 'h ' + r + 'm' : h + 'h') : r + 'm';
            }

            function renderSummary() {
                const sort = document.getElementById('summarySort').value;
                const tbody = document.getElementById('summaryTbody');
                if (!tbody) return;

                const rows = [...summaryData].sort((a, b) => {
                    if (sort === 'name') return a.name.localeCompare(b.name);
                    if (sort === 'days') return b.days - a.days;
                    if (sort === 'hours') return b.hours - a.hours;
                    if (sort === 'late') return b.late - a.late;
                    if (sort === 'undertime') return b.undertime - a.undertime;
                    return 0;
                });

                tbody.innerHTML = rows.map((r, i) => {
                    const pts = (0.42 / 8 * r.hours).toFixed(4);
                    const lateClr = r.late > 0 ? '#B45309' : 'var(--text-secondary)';
                    const utClr = r.undertime > 0 ? '#DC2626' : 'var(--text-secondary)';
                    const hasIssue = r.late > 0 || r.undertime > 0;
                    const statusBadge = !hasIssue ?
                        `<span style="font-size:10px;padding:3px 8px;border-radius:99px;font-weight:600;
                            background:rgba(34,197,94,0.1);color:#16a34a;">Complete</span>` :
                        (r.late > 0 && r.undertime > 0) ?
                        `<span style="font-size:10px;padding:3px 8px;border-radius:99px;font-weight:600;
                                background:rgba(239,68,68,0.1);color:#DC2626;">Late + UT</span>` :
                        r.late > 0 ?
                        `<span style="font-size:10px;padding:3px 8px;border-radius:99px;font-weight:600;
                                    background:rgba(245,158,11,0.1);color:#B45309;">Has Late</span>` :
                        `<span style="font-size:10px;padding:3px 8px;border-radius:99px;font-weight:600;
                                    background:rgba(239,68,68,0.08);color:#DC2626;">Has UT</span>`;

                    const rowBg = i % 2 ? 'rgba(0,0,0,0.013)' : '';
                    return `
        <tr style="background:${rowBg};transition:background 0.1s;"
            onmouseover="this.style.background='rgba(110,168,254,0.04)'"
            onmouseout="this.style.background='${rowBg}'">
            <td style="padding:11px 18px;font-size:11px;color:var(--text-secondary);
                       border-bottom:1px solid var(--border);text-align:center;">
                ${i + 1}
            </td>
            <td style="padding:11px 18px;border-bottom:1px solid var(--border);">
                <span style="font-size:12px;font-weight:600;color:var(--text-primary);">${r.name}</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                <span style="font-size:13px;font-weight:700;color:#22C55E;">${r.days}</span>
                <span style="font-size:10px;color:var(--text-secondary);margin-left:2px;">days</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                <span style="font-size:13px;font-weight:700;color:#4A90E2;">${r.hours}h</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                <span style="font-size:12px;font-weight:600;color:${lateClr};">${fmtMin(r.late)}</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                <span style="font-size:12px;font-weight:600;color:${utClr};">${fmtMin(r.undertime)}</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                <span style="font-size:12px;font-weight:700;color:#059669;">${pts}</span>
            </td>
            <td style="padding:11px 18px;text-align:center;border-bottom:1px solid var(--border);">
                ${statusBadge}
            </td>
        </tr>`;
                }).join('');
            }
        </script>
    @endpush

@endsection

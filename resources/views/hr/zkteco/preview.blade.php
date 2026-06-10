@extends('layouts.hr')
@section('title', 'Import Preview')
@section('page-title', 'Import Preview')

@section('content')

    @php $summary = session('import_summary'); @endphp

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">ZKTeco Import</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Import Preview</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                Review computed attendance records. Print a DTR for any employee.
            </p>
        </div>
    </div>

    {{-- ── Import Summary ── --}}
    @if ($summary)
        <div class="row g-3 anim-fade-up mb-4">
            @foreach ([['bi-file-text', 'File', $summary['filename'], '#4A90E2', 'rgba(110,168,254,0.1)'], ['bi-list-ol', 'Raw Punches', number_format($summary['raw_rows']), '#8B5CF6', 'rgba(139,92,246,0.1)'], ['bi-check-circle', 'Days Saved', number_format($summary['imported']), '#22C55E', 'rgba(52,211,153,0.1)'], ['bi-exclamation-circle', 'Skipped', count($summary['skipped']), '#F59E0B', 'rgba(245,158,11,0.1)']] as [$icon, $label, $value, $color, $bg])
                <div class="col-6 col-md-3">
                    <div class="stat-card" style="text-align:center;padding:16px 12px;">
                        <div
                            style="width:36px;height:36px;border-radius:10px;background:{{ $bg }};
                 display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                            <i class="bi {{ $icon }}" style="color:{{ $color }};font-size:15px;"></i>
                        </div>
                        <div style="font-size:16px;font-weight:700;color:var(--text-primary);">{{ $value }}</div>
                        <div style="font-size:11px;color:var(--text-secondary);">{{ $label }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (!empty($summary['skipped']))
            <div class="alert alert-warning anim-fade-up mb-4" style="font-size:12px;">
                <strong><i class="bi bi-exclamation-triangle me-1"></i>Skipped employees:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($summary['skipped'] as $s)
                        <li>{{ $s }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    {{-- ── Filters ── --}}
    <div class="stat-card anim-fade-up delay-1 mb-4">
        <form method="GET" action="{{ route('hr.attendance.index') }}"
            style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end;">
            <input type="hidden" name="file" value="{{ $filename }}">

            <div>
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                   text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">Month</label>
                <input type="month" name="month" class="form-control form-control-sm" style="font-size:12px;"
                    value="{{ $month ?? '' }}">
            </div>

            <button type="submit" class="btn btn-outline-primary btn-sm" style="font-size:12px;">
                <i class="bi bi-search me-1"></i> Filter
            </button>
            <a href="{{ route('hr.attendance.index', ['file' => $filename]) }}" class="btn btn-outline-secondary btn-sm"
                style="font-size:12px;">
                <i class="bi bi-x me-1"></i> Clear
            </a>
            <a href="{{ route('hr.zkteco.upload') }}" class="btn btn-outline-success btn-sm ms-auto"
                style="font-size:12px;">
                <i class="bi bi-upload me-1"></i> Upload Another File
            </a>
        </form>
    </div>

    {{-- ── Per Employee Summary Cards with Print DTR ── --}}
    @forelse($byEmployee as $userId => $attRecords)
        @php
            $emp = $attRecords->first()->employee;
            $totalLate = $attRecords->sum('late_minutes');
            $totalUndertime = $attRecords->sum('undertime_minutes');
            $totalHours = $attRecords->sum('total_hours');
            $daysPresent = $attRecords->count();
            // Determine month from records
            $firstDate = $attRecords->first()->t_date;
            $monthParam = $firstDate ? $firstDate->format('Y-m') : now()->format('Y-m');
        @endphp
        <div class="stat-card anim-fade-up mb-3">
            {{-- Employee header --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;
         padding-bottom:0.75rem;margin-bottom:0.75rem;border-bottom:1px solid var(--border);
         flex-wrap:wrap;gap:8px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div
                        style="width:36px;height:36px;border-radius:50%;
                 background:linear-gradient(135deg,#6EA8FE,#4A90E2);
                 display:flex;align-items:center;justify-content:center;
                 color:white;font-weight:700;font-size:14px;flex-shrink:0;">
                        {{ strtoupper(substr($attRecords->first()->fullname ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text-primary);">
                            {{ $attRecords->first()->fullname }}
                        </div>
                        <div style="font-size:11px;color:var(--text-secondary);">
                            {{ $attRecords->first()->position ?? 'N/A' }} &bull; {{ $daysPresent }} day(s) present
                        </div>
                    </div>
                </div>

                {{-- Print DTR Button --}}
                <a href="{{ route('hr.zkteco.dtr', ['employee_id' => $userId, 'month' => $monthParam]) }}" target="_blank"
                    class="btn btn-sm"
                    style="background:linear-gradient(135deg,#6EA8FE,#4A90E2);color:white;
                  font-size:12px;font-weight:600;border:none;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-printer"></i> Print DTR ({{ Carbon\Carbon::parse($monthParam)->format('M Y') }})
                </a>
            </div>

            {{-- Summary stats --}}
            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:12px;">
                <div style="text-align:center;">
                    <div style="font-size:15px;font-weight:700;color:#22C55E;">{{ number_format($totalHours, 1) }}h</div>
                    <div style="font-size:10px;color:var(--text-secondary);">Total Hours</div>
                </div>
                <div style="text-align:center;">
                    <div
                        style="font-size:15px;font-weight:700;color:{{ $totalLate > 0 ? '#F59E0B' : 'var(--text-secondary)' }};">
                        {{ $totalLate > 0 ? floor($totalLate / 60) . 'h ' . $totalLate % 60 . 'm' : '—' }}
                    </div>
                    <div style="font-size:10px;color:var(--text-secondary);">Total Late</div>
                </div>
                <div style="text-align:center;">
                    <div
                        style="font-size:15px;font-weight:700;color:{{ $totalUndertime > 0 ? '#EF4444' : 'var(--text-secondary)' }};">
                        {{ $totalUndertime > 0 ? floor($totalUndertime / 60) . 'h ' . $totalUndertime % 60 . 'm' : '—' }}
                    </div>
                    <div style="font-size:10px;color:var(--text-secondary);">Total Undertime</div>
                </div>
            </div>

            {{-- Day-by-day breakdown --}}
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                Date</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                AM In</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                AM Out</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                PM In</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                PM Out</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                Hours</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                Late</th>
                            <th
                                style="font-size:10px;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.06em;color:var(--text-secondary);padding:6px 8px;
                        border-bottom:2px solid var(--border);">
                                Undertime</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attRecords->sortBy('t_date') as $att)
                            <tr style="transition:background 0.15s;">
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    <div style="font-weight:600;color:var(--text-primary);">
                                        {{ $att->t_date?->format('D, M d') }}
                                    </div>
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->am_time_in)
                                        <span
                                            style="font-size:11px;font-weight:600;padding:2px 6px;
                                  border-radius:4px;background:rgba(110,168,254,0.1);color:#4A90E2;">
                                            {{ $att->am_time_in }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);">—</span>
                                    @endif
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->am_time_out)
                                        <span
                                            style="font-size:11px;padding:2px 6px;border-radius:4px;
                                  background:rgba(110,168,254,0.06);color:#4A90E2;">
                                            {{ $att->am_time_out }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);">—</span>
                                    @endif
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->pm_time_in)
                                        <span
                                            style="font-size:11px;font-weight:600;padding:2px 6px;
                                  border-radius:4px;background:rgba(245,158,11,0.1);color:#F59E0B;">
                                            {{ $att->pm_time_in }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);">—</span>
                                    @endif
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->pm_time_out)
                                        <span
                                            style="font-size:11px;padding:2px 6px;border-radius:4px;
                                  background:rgba(245,158,11,0.06);color:#F59E0B;">
                                            {{ $att->pm_time_out }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-secondary);">—</span>
                                    @endif
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    <span class="status-badge badge-info" style="font-size:11px;">
                                        {{ $att->total_hours }}h
                                    </span>
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->late_minutes > 0)
                                        <span
                                            style="font-size:11px;font-weight:600;padding:2px 6px;
                                  border-radius:4px;background:rgba(245,158,11,0.1);color:#D97706;">
                                            {{ floor($att->late_minutes / 60) > 0 ? floor($att->late_minutes / 60) . 'h ' : '' }}{{ $att->late_minutes % 60 }}m
                                        </span>
                                    @else
                                        <span style="font-size:11px;color:#22C55E;font-weight:600;">✓</span>
                                    @endif
                                </td>
                                <td style="padding:7px 8px;vertical-align:middle;">
                                    @if ($att->undertime_minutes > 0)
                                        <span
                                            style="font-size:11px;font-weight:600;padding:2px 6px;
                                  border-radius:4px;background:rgba(239,68,68,0.1);color:#DC2626;">
                                            {{ floor($att->undertime_minutes / 60) > 0 ? floor($att->undertime_minutes / 60) . 'h ' : '' }}{{ $att->undertime_minutes % 60 }}m
                                        </span>
                                    @else
                                        <span style="font-size:11px;color:#22C55E;font-weight:600;">✓</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="stat-card" style="text-align:center;padding:3rem;color:var(--text-secondary);">
            <i class="bi bi-clock" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;"></i>
            <div style="font-size:13px;">No attendance records found for this import.</div>
            <a href="{{ route('hr.zkteco.upload') }}" class="btn btn-primary btn-sm mt-3">
                <i class="bi bi-upload me-1"></i> Upload a File
            </a>
        </div>
    @endforelse

@endsection

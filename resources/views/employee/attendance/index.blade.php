@extends('layouts.employee')
@section('title', 'My Attendance')
@section('page-title', 'Attendance')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Attendance</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">My Attendance & Leave Points</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">View your daily attendance records and accumulated points.</p>
        </div>
    </div>

    {{-- Points Summary --}}
    <div class="row g-3 mb-4 anim-fade-up delay-1">
        <div class="col-md-4">
            <div class="stat-card text-center" style="padding:1.75rem;">
                <div
                    style="width:56px;height:56px;border-radius:16px;margin:0 auto 12px;
                        background:linear-gradient(135deg,var(--accent-light),var(--accent));
                        display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-star-fill" style="font-size:22px;color:white;"></i>
                </div>
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);
                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">
                    Total Leave Points
                </div>
                <div style="font-size:36px;font-weight:700;color:var(--text-primary);line-height:1;">
                    {{ round($totalPoints, 2) }}
                </div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:4px;">
                    accumulated points
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="stat-card h-100 d-flex align-items-center">
                <div
                    style="background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.2);
                        border-radius:var(--radius-sm);padding:14px 16px;width:100%;">
                    <div style="font-size:12px;font-weight:700;color:#1D4ED8;margin-bottom:6px;">
                        <i class="bi bi-calculator me-1"></i> How Points Are Calculated
                    </div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.7;">
                        Leave points are computed as:
                        <strong style="color:var(--text-primary);">(0.42 ÷ 8) × total hours worked</strong> per day.<br>
                        Every full 8-hour day = <strong style="color:var(--text-primary);">0.42 points</strong> earned.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="stat-card anim-fade-up delay-2">
        <div
            style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
            <i class="bi bi-clock-history me-2" style="color:#8B5CF6;"></i>Attendance Records
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>AM In</th>
                        <th>AM Out</th>
                        <th>PM In</th>
                        <th>PM Out</th>
                        <th>Total Hours</th>
                        <th>Points Earned</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendance as $att)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">
                                {{ $att->t_date?->format('M d, Y') }}
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $att->am_time_in ?? '—' }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $att->am_time_out ?? '—' }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $att->pm_time_in ?? '—' }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $att->pm_time_out ?? '—' }}</td>
                            <td><span class="status-badge badge-info">{{ $att->total_hours }}h</span></td>
                            <td>
                                <span style="font-weight:700;color:#8B5CF6;font-size:13.5px;">
                                    {{ round((0.42 / 8) * (float) $att->total_hours, 4) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-clock"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No attendance records yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $attendance->links() }}</div>
    </div>

@endsection

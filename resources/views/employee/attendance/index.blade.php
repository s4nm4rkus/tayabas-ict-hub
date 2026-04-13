@extends('layouts.employee')

@section('title', 'My Attendance')
@section('page-title', 'My Attendance & Leave Points')

@section('content')

    {{-- Points Summary --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card text-center">
                <div style="font-size:12px;color:#8892a4;margin-bottom:4px;">
                    Total Accumulated Points
                </div>
                <div style="font-size:32px;font-weight:600;color:#4f8ef7;">
                    {{ round($totalPoints, 4) }}
                </div>
                <div style="font-size:12px;color:#8892a4;">leave points earned</div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="stat-card">
                <div style="font-size:13px;color:#555;line-height:1.7;">
                    <i class="bi bi-info-circle me-1 text-primary"></i>
                    Leave points are computed as:
                    <strong>(0.42 ÷ 8) × total hours worked</strong> per day.
                    Every 8 hours = 0.42 points earned.
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="stat-card">
        <div class="info-card-title">
            <i class="bi bi-clock-history"></i> Attendance Records
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
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
                            <td>{{ $att->t_date?->format('M d, Y') }}</td>
                            <td>{{ $att->am_time_in ?? '—' }}</td>
                            <td>{{ $att->am_time_out ?? '—' }}</td>
                            <td>{{ $att->pm_time_in ?? '—' }}</td>
                            <td>{{ $att->pm_time_out ?? '—' }}</td>
                            <td>{{ $att->total_hours }}</td>
                            <td style="color:#4f8ef7;font-weight:500;">
                                {{ round((0.42 / 8) * (float) $att->total_hours, 4) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No attendance records yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendance->links() }}
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

@endsection

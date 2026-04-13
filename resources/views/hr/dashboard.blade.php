@extends('layouts.hr')

@section('title', 'HR Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Leaves</div>
                    <div class="stat-icon" style="background:#fff3cd;">
                        <i class="bi bi-calendar-check" style="color:#f0ad4e;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingLeaves }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Certificates</div>
                    <div class="stat-icon" style="background:#d4edda;">
                        <i class="bi bi-file-earmark-text" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCerts }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Total Employees</div>
                    <div class="stat-icon" style="background:#e8f0fe;">
                        <i class="bi bi-people" style="color:#4f8ef7;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalEmployees }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Approved Today</div>
                    <div class="stat-icon" style="background:#d4edda;">
                        <i class="bi bi-check-circle" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $approvedToday }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:15px;font-weight:600;">Pending Leave Requests</h6>
                <table class="table table-sm table-hover">
                    <thead>
                        <tr style="font-size:13px;color:#8892a4;">
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>Days</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        @forelse($recentLeaves as $leave)
                            <tr>
                                <td>{{ $leave->fullname }}</td>
                                <td>{{ $leave->leavetype }}</td>
                                <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                                <td>{{ $leave->total_days }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No pending leaves.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <a href="{{ route('hr.leave.index') }}" class="btn btn-outline-primary btn-sm mt-2">
                    View All
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:15px;font-weight:600;">Announcements</h6>
                @forelse($announcements as $post)
                    <div style="padding:10px 0;border-bottom:1px solid #f5f5f5;">
                        <div style="font-size:13px;font-weight:500;">{{ $post->title }}</div>
                        <div style="font-size:12px;color:#8892a4;">
                            {{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted" style="font-size:13px;">No announcements.</p>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #1a1f2e;
        }

        .stat-label {
            font-size: 13px;
            color: #8892a4;
        }
    </style>

@endsection

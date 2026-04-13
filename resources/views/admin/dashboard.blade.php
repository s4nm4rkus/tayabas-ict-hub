@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
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
                        <i class="bi bi-file-earmark-check" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCerts }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Present Today</div>
                    <div class="stat-icon" style="background:#f8d7da;">
                        <i class="bi bi-clock" style="color:#dc3545;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $presentToday }}</div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-3">
        <div class="col-md-8">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:15px;font-weight:600;">
                    Recent Leave Requests
                </h6>
                <table class="table table-sm table-hover">
                    <thead>
                        <tr style="font-size:13px;color:#8892a4;">
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        @forelse($recentLeaves as $leave)
                            <tr>
                                <td>{{ $leave->fullname }}</td>
                                <td>{{ $leave->leavetype }}</td>
                                <td>{{ $leave->date_applied?->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $color = match (true) {
                                            str_contains($leave->leave_status, 'Approved')
                                                => 'background:#d4edda;color:#155724;',
                                            str_contains($leave->leave_status, 'Declined')
                                                => 'background:#f8d7da;color:#721c24;',
                                            default => 'background:#fff3cd;color:#856404;',
                                        };
                                    @endphp
                                    <span class="badge" style="{{ $color }}font-size:12px;">
                                        {{ $leave->leave_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No recent leave requests.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:15px;font-weight:600;">
                    Recent Announcements
                </h6>
                @forelse($recentAnnouncements as $post)
                    <div style="padding:10px 0;border-bottom:1px solid #f5f5f5;">
                        <div style="font-size:13px;font-weight:500;color:#1a1f2e;">
                            {{ $post->title }}
                        </div>
                        <div style="font-size:12px;color:#8892a4;margin-top:2px;">
                            {{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted" style="font-size:13px;">No announcements yet.</p>
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

@extends('layouts.asds')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">
                Assistant Schools Division Superintendent
            </div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">
                Welcome, {{ Auth::user()->username }} 👋
            </h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                Leave applications awaiting your final approval.
            </p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card anim-fade-up delay-1" style="text-align:center;">
                <div style="font-size:32px;font-weight:800;color:#4338CA;">{{ $pendingCount }}</div>
                <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-top:4px;">
                    Pending Final Approval
                </div>
                <div style="margin-top:12px;">
                    <a href="{{ route('asds.leave.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-eye me-1"></i> View All
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card anim-fade-up delay-2" style="text-align:center;">
                <div style="font-size:32px;font-weight:800;color:#059669;">{{ $approvedCount }}</div>
                <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-top:4px;">
                    Total Approved
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card anim-fade-up delay-3" style="text-align:center;">
                <div style="font-size:32px;font-weight:800;color:#DC2626;">{{ $declinedCount }}</div>
                <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-top:4px;">
                    Total Disapproved
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent Leave Requests ── --}}
    <div class="stat-card anim-fade-up delay-2">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Recent Leave Requests</div>
            <a href="{{ route('asds.leave.index') }}"
                style="font-size:12px;font-weight:600;color:#4338CA;text-decoration:none;">
                View All →
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLeaves as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</td>
                            <td style="font-size:13px;">
                                {{ Str::limit($leave->leave_types ?? $leave->leavetype, 25) }}
                            </td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td>
                                @php
                                    $cls = match (true) {
                                        $leave->leave_status === 'Approved' => 'badge-success',
                                        $leave->leave_status === 'Declined' => 'badge-danger',
                                        $leave->leave_status === 'Pending ASDS' => 'badge-indigo',
                                        default => 'badge-warning',
                                    };
                                @endphp
                                <span class="status-badge {{ $cls }}">{{ $leave->leave_status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('asds.leave.show', $leave->id) }}"
                                    style="font-size:12px;color:#4338CA;font-weight:600;text-decoration:none;">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                No recent leave requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('layouts.asds')
@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Leave Requests</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Final approval of leave applications.</p>
        </div>
    </div>

    {{-- ── Pending ── --}}
    <div class="stat-card anim-fade-up delay-1 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">
                    Pending Final Approval
                </div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                    Approved by AO — awaiting your final decision
                </div>
            </div>
            <span
                style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                     background:rgba(99,102,241,0.12);color:#4338CA;border:1px solid rgba(99,102,241,0.2);">
                {{ $leaves->count() }} pending
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Filed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->position }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->department ?? '—' }}</td>
                            <td>
                                <span class="status-badge badge-indigo"
                                    style="background:rgba(99,102,241,0.12);color:#4338CA;">
                                    {{ Str::limit($leave->leave_types ?? $leave->leavetype, 20) }}
                                </span>
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td style="font-size:12px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('asds.leave.show', $leave->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-calendar-check"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No pending leave requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Recently Processed ── --}}
    <div class="stat-card anim-fade-up delay-2">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Recently Processed</div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Last 20 processed requests</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processed as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</td>
                            <td style="font-size:13px;">{{ Str::limit($leave->leave_types ?? $leave->leavetype, 25) }}</td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td>
                                @php
                                    $cls = match (true) {
                                        $leave->leave_status === 'Approved' => 'badge-success',
                                        $leave->leave_status === 'Declined' => 'badge-danger',
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
                            <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                No processed leaves yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

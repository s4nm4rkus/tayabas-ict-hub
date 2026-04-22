@extends('layouts.employee')
@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">My Leave Applications</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Track your leave requests and their status.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Leave History</div>
            <a href="{{ route('employee.leave.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus me-1"></i> Apply for Leave
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Applied</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->leavetype }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') ?? '—' }}
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') ?? '—' }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td>
                                @php
                                    $cls = match (true) {
                                        str_contains($leave->leave_status, 'Approved') => 'badge-success',
                                        str_contains($leave->leave_status, 'Declined') => 'badge-danger',
                                        str_contains($leave->leave_status, 'Cancelled') => 'badge-gray',
                                        default => 'badge-warning',
                                    };
                                @endphp
                                <span class="status-badge {{ $cls }}">{{ $leave->leave_status }}</span>
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->remarks ?? '—' }}</td>
                            <td>
                                @if (in_array($leave->leave_status, ['Pending HR', 'Pending Head']))
                                    <form method="POST" action="{{ route('employee.leave.cancel', $leave->id) }}"
                                        onsubmit="return confirm('Cancel this leave application?')">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                           border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                           padding:5px 12px;font-size:12px;font-weight:600;cursor:pointer;">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size:12px;color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-calendar"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No leave applications yet.
                                <div class="mt-2">
                                    <a href="{{ route('employee.leave.create') }}" class="btn btn-primary btn-sm">
                                        Apply for Leave
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

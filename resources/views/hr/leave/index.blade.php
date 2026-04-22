@extends('layouts.hr')
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
            <p style="font-size:13px;opacity:0.8;margin:0;">Review and process employee leave applications.</p>
        </div>
    </div>

    {{-- Pending --}}
    <div class="stat-card anim-fade-up delay-1 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">
                    Pending Approval
                </div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                    Awaiting your review
                </div>
            </div>
            <span
                style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                     background:rgba(245,158,11,0.12);color:#B45309;border:1px solid rgba(245,158,11,0.2);">
                {{ $leaves->count() }} pending
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Applied</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</div>
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->position }}</td>
                            <td>
                                <span class="status-badge badge-info">{{ $leave->leavetype }}</span>
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge badge-gray">{{ $leave->total_days }}d</span>
                            </td>
                            <td style="font-size:12px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('hr.leave.approve', $leave->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            style="background:rgba(34,197,94,0.1);color:#15803D;
                                           border:1px solid rgba(34,197,94,0.2);border-radius:8px;
                                           padding:5px 10px;cursor:pointer;transition:all var(--transition);"
                                            onclick="return confirm('Approve this leave?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button
                                        style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                       border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                       padding:5px 10px;cursor:pointer;transition:all var(--transition);"
                                        onclick="openDecline({{ $leave->id }})">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
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

    {{-- Recently Processed --}}
    <div class="stat-card anim-fade-up delay-2">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">
                    Recently Processed
                </div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                    Last 20 processed requests
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processed as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</td>
                            <td style="font-size:13.5px;">{{ $leave->leavetype }}</td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td>
                                @php
                                    $cls = match (true) {
                                        str_contains($leave->leave_status, 'Approved') => 'badge-success',
                                        str_contains($leave->leave_status, 'Declined') => 'badge-danger',
                                        default => 'badge-warning',
                                    };
                                @endphp
                                <span class="status-badge {{ $cls }}">{{ $leave->leave_status }}</span>
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->remarks ?? '—' }}</td>
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

    {{-- Decline Modal --}}
    <div class="modal fade" id="declineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);">
                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                    <h5 class="modal-title" style="font-size:15px;font-weight:700;">Decline Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="declineForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <label class="form-label">Reason for declining <span class="text-danger">*</span></label>
                        <textarea name="remarks" class="form-control" rows="3" maxlength="50" required placeholder="Enter reason..."></textarea>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--border);">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#F87171,#EF4444);color:white;
                               border:none;border-radius:var(--radius-sm);font-weight:600;padding:6px 16px;">
                            Decline Leave
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openDecline(id) {
                document.getElementById('declineForm').action = '/hr/leave/' + id + '/decline';
                new bootstrap.Modal(document.getElementById('declineModal')).show();
            }
        </script>
    @endpush

@endsection

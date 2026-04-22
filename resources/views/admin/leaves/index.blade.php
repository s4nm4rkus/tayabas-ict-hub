@extends('layouts.admin')
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
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Leave Requests</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review and manage all employee leave applications.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">All Leave Applications</div>
        </div>

        {{-- Filters --}}
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <form method="GET" action="{{ route('admin.leaves.index') }}" class="d-flex gap-2 flex-wrap w-100">
                <div style="position:relative;flex:1;min-width:200px;max-width:280px;">
                    <i class="bi bi-search"
                        style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                   color:var(--text-secondary);font-size:14px;pointer-events:none;"></i>
                    <input type="text" name="search" class="form-control" style="padding-left:36px;"
                        placeholder="Search employee..." value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select" style="max-width:180px;">
                    <option value="">All Status</option>
                    @foreach (['Pending HR', 'Pending Head', 'Approved', 'Declined', 'Cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </form>
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
                        <th>Applied</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</div>
                                <div style="font-size:12px;color:var(--text-secondary);">{{ $leave->position }}</div>
                            </td>
                            <td style="font-size:13.5px;">{{ $leave->leavetype }}</td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td><span class="status-badge badge-info">{{ $leave->total_days }}d</span></td>
                            <td style="font-size:13px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') }}</td>
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
                            <td>
                                @if (in_array($leave->leave_status, ['Pending HR', 'Pending Head']))
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm"
                                                style="background:rgba(34,197,94,0.1);color:#15803D;border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:5px 10px;"
                                                onclick="return confirm('Approve this leave?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm"
                                            style="background:rgba(239,68,68,0.1);color:#B91C1C;border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:5px 10px;"
                                            onclick="openDecline({{ $leave->id }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                @else
                                    <span style="font-size:12px;color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-calendar-x"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No leave records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $leaves->links() }}</div>
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
                            style="background:linear-gradient(135deg,#F87171,#EF4444);color:white;border:none;
                               border-radius:var(--radius-sm);font-weight:600;padding:6px 16px;">
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
                document.getElementById('declineForm').action = '/admin/leaves/' + id + '/decline';
                new bootstrap.Modal(document.getElementById('declineModal')).show();
            }
        </script>
    @endpush

@endsection

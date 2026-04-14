@extends('layouts.admin')

@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="stat-card">
        <div class="info-card-title">
            <i class="bi bi-calendar-check"></i> All Leave Requests
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.leaves.index') }}" class="d-flex gap-2 mb-3 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:220px;"
                placeholder="Search employee..." value="{{ request('search') }}">
            <select name="status" class="form-select" style="max-width:180px;">
                <option value="">All Status</option>
                <option value="Pending HR" {{ request('status') == 'Pending HR' ? 'selected' : '' }}>Pending HR</option>
                <option value="Pending Head" {{ request('status') == 'Pending Head' ? 'selected' : '' }}>Pending Head
                </option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Declined" {{ request('status') == 'Declined' ? 'selected' : '' }}>Declined</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-search"></i> Filter
            </button>
            <a href="{{ route('admin.leaves.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
                        <th>Employee</th>
                        <th>Position</th>
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
                            <td style="font-weight:500;">{{ $leave->fullname }}</td>
                            <td>{{ $leave->position }}</td>
                            <td>{{ $leave->leavetype }}</td>
                            <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td>{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>{{ $leave->date_applied?->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $color = match (true) {
                                        str_contains($leave->leave_status, 'Approved')
                                            => 'background:#d4edda;color:#155724;',
                                        str_contains($leave->leave_status, 'Declined')
                                            => 'background:#f8d7da;color:#721c24;',
                                        str_contains($leave->leave_status, 'Cancelled')
                                            => 'background:#f1f1f1;color:#555;',
                                        default => 'background:#fff3cd;color:#856404;',
                                    };
                                @endphp
                                <span class="badge" style="{{ $color }}font-size:12px;">
                                    {{ $leave->leave_status }}
                                </span>
                            </td>
                            <td>
                                @if (in_array($leave->leave_status, ['Pending HR', 'Pending Head']))
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve this leave?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="openDecline({{ $leave->id }})">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:12px;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No leave records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $leaves->links() }}
    </div>

    {{-- Decline Modal --}}
    <div class="modal fade" id="declineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:15px;">Decline Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="declineForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea name="remarks" class="form-control" rows="3" maxlength="50" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                    </div>
                </form>
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

    @push('scripts')
        <script>
            function openDecline(id) {
                document.getElementById('declineForm').action = '/admin/leaves/' + id + '/decline';
                new bootstrap.Modal(document.getElementById('declineModal')).show();
            }
        </script>
    @endpush

@endsection

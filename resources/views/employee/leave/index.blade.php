@extends('layouts.employee')

@section('title', 'My Leaves')
@section('page-title', 'My Leave Applications')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0" style="font-size:16px;font-weight:600;">Leave Applications</h6>
            <a href="{{ route('employee.leave.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus me-1"></i> Apply for Leave
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
                        <th>Type</th>
                        <th>Date Applied</th>
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
                            <td>{{ $leave->leavetype }}</td>
                            <td>{{ $leave->date_applied?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $leave->start_date?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $leave->end_date?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>
                                @php
                                    $statusColor = match (true) {
                                        str_contains($leave->leave_status, 'Approved')
                                            => 'background:#d4edda;color:#155724;',
                                        str_contains($leave->leave_status, 'Declined')
                                            => 'background:#f8d7da;color:#721c24;',
                                        str_contains($leave->leave_status, 'Cancelled')
                                            => 'background:#f1f1f1;color:#555;',
                                        default => 'background:#fff3cd;color:#856404;',
                                    };
                                @endphp
                                <span class="badge" style="{{ $statusColor }}font-size:12px;">
                                    {{ $leave->leave_status }}
                                </span>
                            </td>
                            <td>{{ $leave->remarks ?? '—' }}</td>
                            <td>
                                @if (in_array($leave->leave_status, ['Pending HR', 'Pending Head']))
                                    <form method="POST" action="{{ route('employee.leave.cancel', $leave->id) }}"
                                        onsubmit="return confirm('Cancel this leave application?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size:12px;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No leave applications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }
    </style>

@endsection

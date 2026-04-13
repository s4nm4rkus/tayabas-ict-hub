@extends('layouts.head')

@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pending --}}
    <div class="stat-card mb-4">
        <div class="info-card-title">
            <i class="bi bi-hourglass-split"></i> Pending Your Approval
            <span style="font-size:12px;font-weight:400;color:#8892a4;margin-left:8px;">
                {{ $leaves->count() }} pending
            </span>
        </div>

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
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('head.leave.approve', $leave->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Endorse this leave to HR?')">
                                            <i class="bi bi-check-lg"></i> Endorse
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="openDecline({{ $leave->id }})">
                                        <i class="bi bi-x-lg"></i> Decline
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No pending leave requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recently Processed --}}
    <div class="stat-card">
        <div class="info-card-title">
            <i class="bi bi-clock-history"></i> Recently Processed
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processed as $leave)
                        <tr>
                            <td style="font-weight:500;">{{ $leave->fullname }}</td>
                            <td>{{ $leave->leavetype }}</td>
                            <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td>{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td>
                                @php
                                    $color = match (true) {
                                        str_contains($leave->leave_status, 'Approved')
                                            => 'background:#d4edda;color:#155724;',
                                        str_contains($leave->leave_status, 'Pending HR')
                                            => 'background:#e8f0fe;color:#1a56db;',
                                        default => 'background:#f8d7da;color:#721c24;',
                                    };
                                @endphp
                                <span class="badge" style="{{ $color }}font-size:12px;">
                                    {{ $leave->leave_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
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
                            <textarea name="remarks" class="form-control" rows="3" maxlength="50" placeholder="Enter reason..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            Decline Leave
                        </button>
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
                document.getElementById('declineForm').action = '/head/leave/' + id + '/decline';
                new bootstrap.Modal(document.getElementById('declineModal')).show();
            }
        </script>
    @endpush

@endsection

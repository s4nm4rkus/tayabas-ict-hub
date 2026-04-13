@extends('layouts.hr')

@section('title', 'Certificate Requests')
@section('page-title', 'Certificate Requests')

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
            <i class="bi bi-hourglass-split"></i> Pending Requests
            <span style="font-size:12px;font-weight:400;color:#8892a4;margin-left:8px;">
                {{ $pending->count() }} pending
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Date Requested</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $req)
                        <tr>
                            <td style="font-weight:500;">
                                {{ $req->employee?->full_name ?? '—' }}
                            </td>
                            <td>{{ $req->employee?->user?->user_pos ?? '—' }}</td>
                            <td>
                                <span style="font-weight:500;">{{ $req->req_type }}</span>
                                <div style="font-size:12px;color:#8892a4;">
                                    @php
                                        $labels = [
                                            'CSR' => 'Service Record',
                                            'COE' => 'Certificate of Employment',
                                            'COEC' => 'COE with Compensation',
                                            'CNA' => 'No Accountability',
                                            'CLB' => 'Leave Balance',
                                        ];
                                    @endphp
                                    {{ $labels[$req->req_type] ?? '' }}
                                </div>
                            </td>
                            <td>{{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}</td>
                            <td>{{ $req->time_req ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('hr.certificates.accept', $req->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Accept this request?')">
                                            <i class="bi bi-check-lg"></i> Accept
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('hr.certificates.decline', $req->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Decline this request?')">
                                            <i class="bi bi-x-lg"></i> Decline
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No pending certificate requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Processed --}}
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
                        <th>Date Requested</th>
                        <th>Approved Date</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($processed as $req)
                        <tr>
                            <td style="font-weight:500;">{{ $req->employee?->full_name ?? '—' }}</td>
                            <td>{{ $req->req_type }}</td>
                            <td>{{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}</td>
                            <td>{{ $req->approve_date ? \Carbon\Carbon::parse($req->approve_date)->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                @php
                                    $color =
                                        $req->req_status === 'Accepted'
                                            ? 'background:#d4edda;color:#155724;'
                                            : 'background:#f8d7da;color:#721c24;';
                                @endphp
                                <span class="badge" style="{{ $color }}font-size:12px;">
                                    {{ $req->req_status }}
                                </span>
                                @if ($req->req_status === 'Accepted')
                                    <a href="{{ route('hr.certificates.pdf', $req->id) }}"
                                        class="btn btn-sm btn-outline-primary ms-1" target="_blank">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No processed requests yet.
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

@endsection

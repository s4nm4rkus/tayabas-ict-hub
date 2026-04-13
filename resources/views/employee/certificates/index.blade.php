@extends('layouts.employee')

@section('title', 'Certificate Requests')
@section('page-title', 'My Certificate Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Request Form --}}
        <div class="col-md-4">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-file-earmark-plus"></i> New Request
                </div>
                <form method="POST" action="{{ route('employee.certificates.store') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size:13px;">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Certificate Type <span class="text-danger">*</span></label>
                        <select name="req_type" class="form-select" required>
                            <option value="">Select type</option>
                            <option value="CSR" {{ old('req_type') == 'CSR' ? 'selected' : '' }}>CSR — Service Record
                            </option>
                            <option value="COE" {{ old('req_type') == 'COE' ? 'selected' : '' }}>COE — Certificate of
                                Employment</option>
                            <option value="COEC" {{ old('req_type') == 'COEC' ? 'selected' : '' }}>COEC — COE with
                                Compensation</option>
                            <option value="CNA" {{ old('req_type') == 'CNA' ? 'selected' : '' }}>CNA — No Accountability
                            </option>
                            <option value="CLB" {{ old('req_type') == 'CLB' ? 'selected' : '' }}>CLB — Leave Balance
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-1"></i> Submit Request
                    </button>
                </form>
            </div>

            {{-- Certificate descriptions --}}
            <div class="stat-card mt-3">
                <div class="info-card-title">
                    <i class="bi bi-info-circle"></i> Certificate Types
                </div>
                <div style="font-size:13px;">
                    @foreach ([
            'CSR' => 'Certificate of Service Record',
            'COE' => 'Certificate of Employment',
            'COEC' => 'COE with Compensation',
            'CNA' => 'Certificate of No Accountability',
            'CLB' => 'Certificate of Leave Balance',
        ] as $code => $desc)
                        <div style="padding:6px 0;border-bottom:1px solid #f5f5f5;">
                            <span style="font-weight:500;color:#1a1f2e;">{{ $code }}</span>
                            <span style="color:#8892a4;"> — {{ $desc }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Request History --}}
        <div class="col-md-8">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-clock-history"></i> My Requests
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size:14px;">
                        <thead>
                            <tr style="font-size:13px;color:#8892a4;">
                                <th>Type</th>
                                <th>Date Requested</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Approved Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
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
                                    <td>{{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}
                                    </td>
                                    <td>{{ $req->time_req ?? '—' }}</td>
                                    <td>
                                        @php
                                            $color = match ($req->req_status) {
                                                'Accepted' => 'background:#d4edda;color:#155724;',
                                                'Declined' => 'background:#f8d7da;color:#721c24;',
                                                default => 'background:#fff3cd;color:#856404;',
                                            };
                                        @endphp
                                        <span class="badge" style="{{ $color }}font-size:12px;">
                                            {{ $req->req_status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $req->approve_date ? \Carbon\Carbon::parse($req->approve_date)->format('M d, Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No certificate requests yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

@endsection

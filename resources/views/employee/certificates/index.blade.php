@extends('layouts.employee')
@section('title', 'Certificate Requests')
@section('page-title', 'Certificate Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Certificates</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Certificate Requests</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Request official certificates from HR.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Request Form --}}
        <div class="col-md-4 anim-fade-up delay-1">
            <div class="stat-card mb-3">
                <div
                    style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-file-earmark-plus me-2" style="color:#8B5CF6;"></i>New Request
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('employee.certificates.store') }}">
                    @csrf
                    <div class="mb-4">
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

            {{-- Certificate types info --}}
            <div class="stat-card">
                <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:10px;">
                    Certificate Types
                </div>
                @foreach ([['CSR', 'Service Record', '#4A90E2'], ['COE', 'Certificate of Employment', '#22C55E'], ['COEC', 'COE with Compensation', '#F59E0B'], ['CNA', 'No Accountability', '#8B5CF6'], ['CLB', 'Leave Balance', '#EF4444']] as [$code, $desc, $color])
                    <div
                        style="display:flex;align-items:center;gap:10px;
                        padding:8px 0;border-bottom:1px solid var(--border);">
                        <span
                            style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;
                             background:{{ $color }}18;color:{{ $color }};flex-shrink:0;min-width:44px;text-align:center;">
                            {{ $code }}
                        </span>
                        <span style="font-size:12px;color:var(--text-secondary);">{{ $desc }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Request History --}}
        <div class="col-md-8 anim-fade-up delay-2">
            <div class="stat-card">
                <div
                    style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    My Requests
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Date Requested</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td>
                                        <span class="status-badge badge-info">{{ $req->req_type }}</span>
                                        <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">
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
                                    <td style="font-size:13px;">
                                        {{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}
                                    </td>
                                    <td style="font-size:12px;color:var(--text-secondary);">{{ $req->time_req ?? '—' }}
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge {{ match ($req->req_status) {
                                                'Accepted' => 'badge-success',
                                                'Declined' => 'badge-danger',
                                                default => 'badge-warning',
                                            } }}">{{ $req->req_status }}</span>
                                    </td>
                                    <td style="font-size:13px;color:var(--text-secondary);">
                                        {{ $req->approve_date ? \Carbon\Carbon::parse($req->approve_date)->format('M d, Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                        <i class="bi bi-file-earmark"
                                            style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
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

@endsection

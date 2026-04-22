@extends('layouts.hr')
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
            <p style="font-size:13px;opacity:0.8;margin:0;">Accept, decline and generate employee certificates.</p>
        </div>
    </div>

    {{-- Pending --}}
    <div class="stat-card anim-fade-up delay-1 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Pending Requests</div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Awaiting your action</div>
            </div>
            <span
                style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                     background:rgba(245,158,11,0.12);color:#B45309;border:1px solid rgba(245,158,11,0.2);">
                {{ $pending->count() }} pending
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Requested</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $req)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:13.5px;">
                                    {{ $req->employee?->full_name ?? '—' }}
                                </div>
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">
                                {{ $req->employee?->user?->user_pos ?? '—' }}
                            </td>
                            <td>
                                @php
                                    $certLabels = [
                                        'CSR' => 'Service Record',
                                        'COE' => 'Employment',
                                        'COEC' => 'COE w/ Compensation',
                                        'CNA' => 'No Accountability',
                                        'CLB' => 'Leave Balance',
                                    ];
                                @endphp
                                <div>
                                    <span class="status-badge badge-info">{{ $req->req_type }}</span>
                                    <div style="font-size:11px;color:var(--text-secondary);margin-top:3px;">
                                        {{ $certLabels[$req->req_type] ?? '' }}
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:13px;">
                                {{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $req->time_req ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('hr.certificates.accept', $req->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            style="background:rgba(34,197,94,0.1);color:#15803D;
                                           border:1px solid rgba(34,197,94,0.2);border-radius:8px;
                                           padding:5px 10px;cursor:pointer;"
                                            onclick="return confirm('Accept this request?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('hr.certificates.decline', $req->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                           border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                           padding:5px 10px;cursor:pointer;"
                                            onclick="return confirm('Decline this request?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-file-earmark-check"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No pending certificate requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Processed --}}
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
                        <th>Type</th>
                        <th>Requested</th>
                        <th>Approved</th>
                        <th>Status</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processed as $req)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $req->employee?->full_name ?? '—' }}</td>
                            <td><span class="status-badge badge-info">{{ $req->req_type }}</span></td>
                            <td style="font-size:13px;">
                                {{ $req->date_req ? \Carbon\Carbon::parse($req->date_req)->format('M d, Y') : '—' }}
                            </td>
                            <td style="font-size:13px;">
                                {{ $req->approve_date ? \Carbon\Carbon::parse($req->approve_date)->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                <span
                                    class="status-badge {{ $req->req_status === 'Accepted' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $req->req_status }}
                                </span>
                            </td>
                            <td>
                                @if ($req->req_status === 'Accepted')
                                    <a href="{{ route('hr.certificates.pdf', $req->id) }}" target="_blank"
                                        style="display:inline-flex;align-items:center;gap:5px;
                                      font-size:12px;font-weight:600;padding:4px 10px;
                                      background:rgba(239,68,68,0.08);color:#B91C1C;
                                      border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                      text-decoration:none;transition:all var(--transition);">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                @else
                                    <span style="font-size:12px;color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                No processed requests yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('layouts.hr')
@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')

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
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Leave Balances</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Current VL/SL balances per employee.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Employee Balances</div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                    Employees without an opening balance yet won't appear here.
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('hr.leave-balances.monthly-computation.form') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-calculator me-1"></i> Compute Monthly
                </a>
                <span
                    style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                             background:rgba(110,168,254,0.12);color:#1D4ED8;border:1px solid rgba(110,168,254,0.2);">
                    {{ $balances->count() }} employees
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>VL Balance</th>
                        <th>SL Balance</th>
                        <th>As of</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">
                                {{ $balance->employee->full_name ?? 'Unknown Employee' }}
                            </td>
                            <td><span class="status-badge badge-info">{{ $balance->vl_balance }}</span></td>
                            <td><span class="status-badge badge-info">{{ $balance->sl_balance }}</span></td>
                            <td style="font-size:12px;color:var(--text-secondary);">
                                {{ $balance->as_of_date?->format('M d, Y') ?? '—' }}
                            </td>
                            <td>
                                <a href="{{ route('hr.leave-balances.show', $balance->employee_id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-wallet2"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No leave balances recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

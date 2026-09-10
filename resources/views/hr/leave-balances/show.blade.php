@extends('layouts.hr')
@section('title', 'Employee Leave Balance')
@section('page-title', 'Employee Leave Balance')

@section('content')

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('hr.leave-balances.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <a href="{{ route('hr.employees.show', $employee->user_id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-person me-1"></i> View Profile
        </a>
        <a href="{{ route('hr.leave-balances.history', $employeeId) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history me-1"></i> View History
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">
                {{ $balance->employee->full_name ?? 'Employee' }}
            </h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Current leave balance and adjustment history.</p>
        </div>
    </div>

    {{-- ── Current Balance ── --}}
    @if ($balance)
        <div class="stat-card anim-fade-up delay-1 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(0,0,0,0.06);">
                <i class="bi bi-wallet2 me-2"></i>Current Balance
            </div>

            <div class="table-responsive mb-0">
                <table class="table" style="font-size:13px;max-width:500px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="text-align:center;">Vacation Leave</th>
                            <th style="text-align:center;">Sick Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><em>Balance</em></td>
                            <td style="text-align:center;font-weight:700;font-size:16px;">{{ $balance->vl_balance }}</td>
                            <td style="text-align:center;font-weight:700;font-size:16px;">{{ $balance->sl_balance }}</td>
                        </tr>
                        <tr>
                            <td><em>As of</em></td>
                            <td colspan="2" style="text-align:center;color:var(--text-secondary);">
                                {{ $balance->as_of_date?->format('M d, Y') ?? '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Manual Adjustment ── --}}
        <div class="stat-card anim-fade-up delay-2"
            style="border:1px solid rgba(59,130,246,0.25);background:rgba(59,130,246,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#1D4ED8;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(59,130,246,0.15);">
                <i class="bi bi-pencil-square me-2"></i>Manual Adjustment
            </div>

            <form method="POST" action="{{ route('hr.leave-balances.adjust') }}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employeeId }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">VL Adjustment</label>
                        <input type="number" step="0.001" name="vl_delta" class="form-control"
                            placeholder="e.g. -2 or 1.5" value="{{ old('vl_delta') }}">
                        <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                            Positive to add, negative to subtract.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SL Adjustment</label>
                        <input type="number" step="0.001" name="sl_delta" class="form-control"
                            placeholder="e.g. -2 or 1.5" value="{{ old('sl_delta') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Effective Date</label>
                        <input type="date" name="effective_date" class="form-control"
                            value="{{ old('effective_date', now()->toDateString()) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control" rows="2" maxlength="255" required
                        placeholder="e.g. Verified against signed Leave Card">{{ old('reason') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-2"></i> Save Adjustment
                </button>
            </form>
        </div>
    @else
        {{-- ── No balance yet — Opening Balance form ── --}}
        <div class="stat-card anim-fade-up delay-1"
            style="border:1px solid rgba(245,158,11,0.25);background:rgba(245,158,11,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#B45309;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(245,158,11,0.15);">
                <i class="bi bi-exclamation-circle me-2"></i>No Balance Recorded Yet
            </div>

            <p style="font-size:13px;color:var(--text-secondary);margin-bottom:1.25rem;">
                This employee has no starting leave balance on file. Enter one below to begin tracking.
            </p>

            <form method="POST" action="{{ route('hr.leave-balances.store-opening') }}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employeeId }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">VL Balance</label>
                        <input type="number" step="0.001" min="0" name="vl_balance" class="form-control"
                            placeholder="0" value="{{ old('vl_balance') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SL Balance</label>
                        <input type="number" step="0.001" min="0" name="sl_balance" class="form-control"
                            placeholder="0" value="{{ old('sl_balance') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">As of Date</label>
                        <input type="date" name="as_of_date" class="form-control"
                            value="{{ old('as_of_date', now()->toDateString()) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <input type="text" name="notes" class="form-control" maxlength="255"
                        placeholder="e.g. Migrated from Leave Card as of Dec 2025" value="{{ old('notes') }}">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-2"></i> Save Opening Balance
                </button>
            </form>
        </div>
    @endif

@endsection

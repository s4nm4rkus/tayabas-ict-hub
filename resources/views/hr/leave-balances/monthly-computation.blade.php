@extends('layouts.hr')
@section('title', 'Compute Monthly Leave Credits')
@section('page-title', 'Compute Monthly Leave Credits')

@section('content')

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('hr.leave-balances.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Compute Monthly Leave Credits</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                Runs VL/SL earning for every eligible employee (Permanent, Non-Teaching) for the selected month.
            </p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1 mb-3"
        style="border:1px solid rgba(245,158,11,0.25);background:rgba(245,158,11,0.03);">
        <div style="font-size:12.5px;color:#B45309;">
            <i class="bi bi-exclamation-circle me-1"></i>
            <strong>Known limitation:</strong> LWOP (Leave Without Pay) days are not yet tracked automatically.
            Every computation currently assumes zero LWOP for the month. If an employee genuinely had LWOP days,
            use the Manual Adjustment tool on their balance page afterward to correct it.
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-2 mb-3">
        <div
            style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;
            letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(0,0,0,0.06);">
            <i class="bi bi-calculator me-2"></i>Run Computation
        </div>

        <form method="POST" action="{{ route('hr.leave-balances.monthly-computation.run') }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" min="2020" max="2100"
                        value="{{ old('year', $year ?? now()->year) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select" required>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}"
                                {{ old('month', $month ?? now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2000, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-play-fill me-1"></i> Run Computation
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if (isset($result))
        <div class="stat-card anim-fade-up delay-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(0,0,0,0.06);">
                <i class="bi bi-list-check me-2"></i>Results —
                {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
            </div>

            <div class="d-flex gap-3 mb-3 flex-wrap">
                <span class="status-badge badge-success" style="font-size:12px;">
                    {{ $result['computed'] }} computed
                </span>
                <span class="status-badge badge-warning" style="font-size:12px;">
                    {{ count($result['skipped']) }} skipped
                </span>
            </div>

            @if (count($result['skipped']) > 0)
                <div
                    style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px;">
                    Skipped Employees
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:13px;">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['skipped'] as $employeeId => $reason)
                                <tr>
                                    <td style="font-weight:600;">{{ $employeeId }}</td>
                                    <td style="color:var(--text-secondary);">{{ $reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

@endsection

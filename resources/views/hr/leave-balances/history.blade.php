@extends('layouts.hr')
@section('title', 'Leave Credit History')
@section('page-title', 'Leave Credit History')

@section('content')

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('hr.leave-balances.show', $employeeId) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Balance
        </a>
        <a href="{{ route('hr.leave-balances.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list me-1"></i> Back to List
        </a>
    </div>

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">
                {{ $employee->full_name }} — Leave Credit History
            </h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                Every balance change, in order — opening balance, deductions, monthly earnings, and adjustments.
            </p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Transaction History</div>
            <span
                style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                         background:rgba(110,168,254,0.12);color:#1D4ED8;border:1px solid rgba(110,168,254,0.2);">
                {{ $transactions->count() }} entries
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:12.5px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th style="text-align:center;">VL Change</th>
                        <th style="text-align:center;">SL Change</th>
                        <th style="text-align:center;">VL Balance After</th>
                        <th style="text-align:center;">SL Balance After</th>
                        <th>Description</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        @php
                            $typeBadge = match ($txn->transaction_type) {
                                'OPENING_BALANCE' => ['label' => 'Opening Balance', 'class' => 'badge-info'],
                                'LEAVE_DEDUCTION' => ['label' => 'Leave Deduction', 'class' => 'badge-danger'],
                                'MONTHLY_EARNING' => ['label' => 'Monthly Earning', 'class' => 'badge-success'],
                                'MANUAL_ADJUSTMENT' => ['label' => 'Manual Adjustment', 'class' => 'badge-warning'],
                                default => ['label' => $txn->transaction_type, 'class' => 'badge-gray'],
                            };
                            $vlChanged = (float) $txn->vl_amount !== 0.0;
                            $slChanged = (float) $txn->sl_amount !== 0.0;
                        @endphp
                        <tr>
                            <td style="white-space:nowrap;color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($txn->effective_date)->format('M d, Y') }}
                            </td>
                            <td>
                                <span class="status-badge {{ $typeBadge['class'] }}" style="font-size:11px;">
                                    {{ $typeBadge['label'] }}
                                </span>
                            </td>
                            <td style="text-align:center;font-weight:600;">
                                @if ($vlChanged)
                                    <span style="color:{{ $txn->vl_amount > 0 ? '#059669' : '#DC2626' }};">
                                        {{ $txn->vl_amount > 0 ? '+' : '' }}{{ $txn->vl_amount }}
                                    </span>
                                @else
                                    <span style="color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:600;">
                                @if ($slChanged)
                                    <span style="color:{{ $txn->sl_amount > 0 ? '#059669' : '#DC2626' }};">
                                        {{ $txn->sl_amount > 0 ? '+' : '' }}{{ $txn->sl_amount }}
                                    </span>
                                @else
                                    <span style="color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:700;">{{ $txn->vl_balance_after }}</td>
                            <td style="text-align:center;font-weight:700;">{{ $txn->sl_balance_after }}</td>
                            <td style="color:var(--text-secondary);max-width:280px;">
                                {{ $txn->description ?? '—' }}
                                @if ($txn->reference_type && $txn->reference_id)
                                    <span style="font-size:10px;color:var(--text-secondary);opacity:0.7;">
                                        ({{ $txn->reference_type }} #{{ $txn->reference_id }})
                                    </span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;color:var(--text-secondary);">
                                {{ $txn->createdBy?->username ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-clock-history"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No transactions recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('layouts.employee')
@section('title', 'Leave Application Details')
@section('page-title', 'Leave Application')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">CS Form No. 6 — Details</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">View your leave application and approval status.</p>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('employee.leave.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to My Leaves
        </a>

        {{-- Print button — always visible --}}
        <a href="{{ route('employee.leave.print', $leave->id) }}" target="_blank" class="btn btn-sm"
            style="background:rgba(110,168,254,0.12);color:#1D4ED8;border:1px solid rgba(110,168,254,0.25);
              border-radius:var(--radius-sm);font-weight:600;padding:6px 14px;text-decoration:none;">
            <i class="bi bi-printer me-1"></i> Print Form 6
        </a>

        {{-- Download PDF — only when approved --}}
        @if ($leave->leave_status === 'Approved')
            <a href="{{ route('employee.leave.pdf', $leave->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        @endif
    </div>

    {{-- ── Shared Form 6 Detail (progress tracker + all sections) ── --}}
    @include('shared.leave._form6_detail', ['leave' => $leave])

    {{-- ── If Cancelled ── --}}
    @if ($leave->leave_status === 'Cancelled')
        <div class="stat-card anim-fade-up"
            style="border:1px solid rgba(107,114,128,0.2);background:rgba(107,114,128,0.04);text-align:center;padding:2rem;">
            <i class="bi bi-x-circle" style="font-size:32px;color:#6B7280;margin-bottom:8px;display:block;"></i>
            <div style="font-size:14px;font-weight:600;color:var(--text-secondary);">
                This leave application was cancelled.
            </div>
        </div>
    @endif

    {{-- ── If Declined ── --}}
    @if ($leave->leave_status === 'Declined')
        <div class="stat-card anim-fade-up" style="border:1px solid rgba(239,68,68,0.2);background:rgba(239,68,68,0.04);">
            <div style="font-size:13px;font-weight:700;color:#B91C1C;margin-bottom:8px;">
                <i class="bi bi-x-circle me-2"></i>Leave Declined
            </div>
            @if ($leave->remarks)
                <div style="font-size:13px;color:#7F1D1D;">
                    <strong>Reason:</strong> {{ $leave->remarks }}
                </div>
            @endif
            @if ($leave->asds_disapproval)
                <div style="font-size:13px;color:#7F1D1D;margin-top:4px;">
                    <strong>ASDS Remarks:</strong> {{ $leave->asds_disapproval }}
                </div>
            @endif
        </div>
    @endif

@endsection

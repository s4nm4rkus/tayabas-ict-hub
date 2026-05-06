@extends('layouts.ao')
@section('title', 'Leave Application')
@section('page-title', 'Leave Application')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Form 6 — Leave Application</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review and approve this leave application.</p>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('ao.leave.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Shared Form 6 Detail ── --}}
    @include('shared.leave._form6_detail', ['leave' => $leave])

    {{-- ── Action Panel (only if Pending AO) ──────────────────────────────────── --}}
    @if ($leave->leave_status === 'Pending AO')
        <div class="stat-card anim-fade-up delay-4 mb-3"
            style="border:1px solid rgba(139,92,246,0.25);background:rgba(139,92,246,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#6D28D9;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;
                border-bottom:1px solid rgba(139,92,246,0.15);">
                <i class="bi bi-briefcase me-2"></i>Your Action — Administrative Officer Approval
            </div>

            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:1rem;">
                By clicking <strong>Approve</strong>, this application will be forwarded to the ASDS
                for final approval. Your name will be recorded as the approving officer.
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <form method="POST" action="{{ route('ao.leave.approve', $leave->id) }}"
                    onsubmit="return confirm('Approve and forward to ASDS?')">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i> Approve & Forward to ASDS
                    </button>
                </form>

                <button class="btn btn-sm"
                    style="background:rgba(239,68,68,0.08);color:#B91C1C;
                       border:1px solid rgba(239,68,68,0.15);border-radius:var(--radius-sm);
                       font-weight:600;padding:8px 18px;"
                    onclick="document.getElementById('declinePanel').classList.toggle('d-none')">
                    <i class="bi bi-x-lg me-2"></i> Decline
                </button>
            </div>

            {{-- Decline Form --}}
            <div id="declinePanel" class="d-none mt-3"
                style="background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.15);
                border-radius:var(--radius-sm);padding:16px;">
                <form method="POST" action="{{ route('ao.leave.decline', $leave->id) }}">
                    @csrf @method('PUT')
                    <label class="form-label">Reason for Declining <span class="text-danger">*</span></label>
                    <textarea name="ao_remarks" class="form-control mb-3" rows="3" maxlength="150" required
                        placeholder="Enter reason..."></textarea>
                    <button type="submit" class="btn btn-sm"
                        style="background:linear-gradient(135deg,#F87171,#EF4444);color:white;
                           border:none;border-radius:var(--radius-sm);font-weight:600;padding:8px 18px;"
                        onclick="return confirm('Decline this leave application?')">
                        Confirm Decline
                    </button>
                </form>
            </div>
        </div>
    @endif

@endsection

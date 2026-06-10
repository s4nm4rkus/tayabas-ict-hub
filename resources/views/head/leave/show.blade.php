@extends('layouts.head')
@section('title', 'Leave Application')
@section('page-title', 'Leave Application')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Form 6 — Leave Application</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review and endorse this leave application.</p>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('head.leave.index') }}" class="btn btn-outline-secondary btn-sm">
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

    {{-- ── Action Panel (only if Pending Head) ───────────────────────────────── --}}
    @if ($leave->leave_status === 'Pending Head')
        <div class="stat-card anim-fade-up delay-4 mb-3"
            style="border:1px solid rgba(245,158,11,0.25);background:rgba(245,158,11,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#B45309;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;
                border-bottom:1px solid rgba(245,158,11,0.15);">
                <i class="bi bi-pen me-2"></i>Your Action — Endorsement
            </div>

            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:1rem;">
                By clicking <strong>Endorse</strong>, you are recommending this leave for HR approval.
                Your name will be recorded as the endorsing officer.
            </div>

            <div class="d-flex gap-2 flex-wrap">
                {{-- Endorse --}}
                <form method="POST" action="{{ route('head.leave.approve', $leave->id) }}"
                    onsubmit="return confirm('Endorse this leave application to HR?')">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i> Endorse to HR
                    </button>
                </form>

                {{-- Decline --}}
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
                <form method="POST" action="{{ route('head.leave.decline', $leave->id) }}">
                    @csrf @method('PUT')
                    <label class="form-label">Reason for Declining <span class="text-danger">*</span></label>
                    <textarea name="remarks" class="form-control mb-3" rows="3" maxlength="100" required
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

@extends('layouts.asds')
@section('title', 'Leave Application')
@section('page-title', 'Leave Application')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Form 6 — Final Approval</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review the complete Form 6 and issue final decision.</p>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('asds.leave.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Shared Form 6 Detail --}}
    @include('shared.leave._form6_detail', ['leave' => $leave])

    {{-- Action Panel — only if Pending ASDS --}}
    @if ($leave->leave_status === 'Pending ASDS')
        <div class="stat-card anim-fade-up delay-4 mb-3"
            style="border:1px solid rgba(99,102,241,0.25);background:rgba(99,102,241,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#4338CA;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;
                border-bottom:1px solid rgba(99,102,241,0.15);">
                <i class="bi bi-award me-2"></i>Final Decision
            </div>

            <div style="font-size:13px;color:var(--text-secondary);margin-bottom:1.25rem;">
                By clicking <strong>Final Approve</strong>, this leave application will be marked as
                <strong style="color:#059669;">Approved</strong> and an email notification with the
                Form 6 PDF will be sent to the employee automatically.
            </div>

            {{-- Approve --}}
            <form method="POST" action="{{ route('asds.leave.approve', $leave->id) }}"
                onsubmit="return confirm('Finally approve this leave? An email with PDF will be sent to the employee.')">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i> Final Approve &amp; Send Email
                </button>
            </form>

            <hr style="border-color:var(--border);margin:1.25rem 0;">

            {{-- Disapprove --}}
            <button class="btn btn-sm"
                style="background:rgba(239,68,68,0.08);color:#B91C1C;
                   border:1px solid rgba(239,68,68,0.15);border-radius:var(--radius-sm);
                   font-weight:600;padding:8px 18px;"
                onclick="document.getElementById('disapprovePanel').classList.toggle('d-none')">
                <i class="bi bi-x-lg me-2"></i> Disapprove
            </button>

            <div id="disapprovePanel" class="d-none mt-3"
                style="background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.15);
                border-radius:var(--radius-sm);padding:16px;">
                <form method="POST" action="{{ route('asds.leave.decline', $leave->id) }}">
                    @csrf @method('PUT')
                    <label class="form-label">
                        7D. Disapproved Due To <span class="text-danger">*</span>
                    </label>
                    <textarea name="asds_disapproval" class="form-control mb-3" rows="4" required
                        placeholder="State the reason for disapproval..."></textarea>
                    <div style="font-size:13px;color:var(--text-secondary);margin-bottom:1rem;">
                        An email notification will be sent to the employee upon disapproval.
                    </div>
                    <button type="submit" class="btn btn-sm"
                        style="background:linear-gradient(135deg,#F87171,#EF4444);color:white;
                           border:none;border-radius:var(--radius-sm);font-weight:600;padding:8px 18px;"
                        onclick="return confirm('Disapprove this leave? An email will be sent to the employee.')">
                        Confirm Disapproval &amp; Send Email
                    </button>
                </form>
            </div>
        </div>
    @endif

@endsection

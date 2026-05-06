@extends('layouts.hr')
@section('title', 'Leave Application')
@section('page-title', 'Leave Application')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Form 6 — Leave Application</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review, fill leave credits, and approve.</p>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('hr.leave.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>

        {{-- Print button — always visible --}}
        <a href="{{ route('hr.leave.print', $leave->id) }}" target="_blank" class="btn btn-sm"
            style="background:rgba(110,168,254,0.12);color:#1D4ED8;border:1px solid rgba(110,168,254,0.25);
          border-radius:var(--radius-sm);font-weight:600;padding:6px 14px;text-decoration:none;">
            <i class="bi bi-printer me-1"></i> Print Form 6
        </a>

        {{-- Download PDF — only when approved --}}
        @if ($leave->leave_status === 'Approved')
            <a href="{{ route('hr.leave.pdf', $leave->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Shared Form 6 Detail --}}
    @include('shared.leave._form6_detail', ['leave' => $leave])

    {{-- Action Panel — only if Pending HR --}}
    @if ($leave->leave_status === 'Pending HR')
        <div class="stat-card anim-fade-up delay-4 mb-3"
            style="border:1px solid rgba(59,130,246,0.25);background:rgba(59,130,246,0.03);">
            <div
                style="font-size:13px;font-weight:700;color:#1D4ED8;text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;
                border-bottom:1px solid rgba(59,130,246,0.15);">
                <i class="bi bi-people me-2"></i>7A. Certification of Leave Credits &amp; 7C. Approved For
            </div>

            <form method="POST" action="{{ route('hr.leave.approve', $leave->id) }}">
                @csrf @method('PUT')

                {{-- Leave Credits --}}
                <div
                    style="font-size:12px;font-weight:700;color:var(--text-secondary);
                    text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px;">
                    Leave Credits (Section 7A)
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">As of Date</label>
                        <input type="text" name="hr_as_of" class="form-control" placeholder="e.g. Dec 31, 2024"
                            value="{{ old('hr_as_of') }}">
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table" style="font-size:13px;max-width:500px;">
                        <thead>
                            <tr>
                                <th style="width:40%;"></th>
                                <th style="text-align:center;">Vacation Leave</th>
                                <th style="text-align:center;">Sick Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><em>Total Earned</em></td>
                                <td>
                                    <input type="text" name="vl_earned" class="form-control form-control-sm"
                                        style="text-align:center;" placeholder="0" value="{{ old('vl_earned') }}">
                                </td>
                                <td>
                                    <input type="text" name="sl_earned" class="form-control form-control-sm"
                                        style="text-align:center;" placeholder="0" value="{{ old('sl_earned') }}">
                                </td>
                            </tr>
                            <tr>
                                <td><em>Less this application</em></td>
                                <td>
                                    <input type="text" name="vl_less" class="form-control form-control-sm"
                                        style="text-align:center;" placeholder="0" value="{{ old('vl_less') }}">
                                </td>
                                <td>
                                    <input type="text" name="sl_less" class="form-control form-control-sm"
                                        style="text-align:center;" placeholder="0" value="{{ old('sl_less') }}">
                                </td>
                            </tr>
                            <tr>
                                <td><em>Balance</em></td>
                                <td>
                                    <input type="text" name="vl_balance" class="form-control form-control-sm"
                                        style="text-align:center;font-weight:700;" placeholder="0"
                                        value="{{ old('vl_balance') }}">
                                </td>
                                <td>
                                    <input type="text" name="sl_balance" class="form-control form-control-sm"
                                        style="text-align:center;font-weight:700;" placeholder="0"
                                        value="{{ old('sl_balance') }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Days With/Without Pay (Section 7C) --}}
                <div
                    style="font-size:12px;font-weight:700;color:var(--text-secondary);
                    text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px;">
                    Approved For (Section 7C)
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Days with Pay</label>
                        <input type="text" name="asds_days_with_pay" class="form-control" placeholder="e.g. 3"
                            value="{{ old('asds_days_with_pay') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Days without Pay</label>
                        <input type="text" name="asds_days_without_pay" class="form-control" placeholder="e.g. 0"
                            value="{{ old('asds_days_without_pay') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Others (specify)</label>
                        <input type="text" name="asds_others" class="form-control" placeholder="e.g. Terminal leave"
                            value="{{ old('asds_others') }}">
                    </div>
                </div>

                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:1rem;">
                    By clicking <strong>Approve</strong>, leave credits and approved days will be saved
                    and the application will be forwarded to the Administrative Officer.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i> Approve & Forward to AO
                </button>
            </form>

            {{-- Decline --}}
            <div class="mt-3">
                <button class="btn btn-sm"
                    style="background:rgba(239,68,68,0.08);color:#B91C1C;
                       border:1px solid rgba(239,68,68,0.15);border-radius:var(--radius-sm);
                       font-weight:600;padding:8px 18px;"
                    onclick="document.getElementById('declinePanel').classList.toggle('d-none')">
                    <i class="bi bi-x-lg me-2"></i> Decline
                </button>
                <div id="declinePanel" class="d-none mt-3"
                    style="background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.15);
                    border-radius:var(--radius-sm);padding:16px;">
                    <form method="POST" action="{{ route('hr.leave.decline', $leave->id) }}">
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
        </div>
    @endif

@endsection

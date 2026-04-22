@extends('layouts.employee')
@section('title', 'Apply for Leave')
@section('page-title', 'Apply for Leave')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Leave Application</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Submit a new leave request for approval.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="stat-card anim-fade-up delay-1">
                <div
                    style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;
                    padding-bottom:0.875rem;border-bottom:1px solid var(--border);">
                    Leave Application Form
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.leave.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leavetype" class="form-select" required>
                            <option value="">Select leave type</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->leavetype }}"
                                    {{ old('leavetype') == $type->leavetype ? 'selected' : '' }}>
                                    {{ $type->leavetype }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" maxlength="50" placeholder="Optional reason or note...">{{ old('remarks') }}</textarea>
                        <div class="form-text">Max 50 characters.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="leavefile" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Optional. Medical certificate or supporting document.</div>
                    </div>

                    {{-- Info box --}}
                    <div
                        style="background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.2);
                        border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:1.25rem;">
                        <div style="font-size:12px;font-weight:600;color:#1D4ED8;margin-bottom:4px;">
                            <i class="bi bi-info-circle me-1"></i> Leave Flow
                        </div>
                        <div style="font-size:12px;color:var(--text-secondary);line-height:1.6;">
                            Teaching staff → Pending HR → Approved/Declined<br>
                            Non-Teaching → Pending Head → Pending HR → Approved/Declined
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-2"></i> Submit Application
                        </button>
                        <a href="{{ route('employee.leave.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

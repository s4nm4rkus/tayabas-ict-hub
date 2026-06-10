@extends('layouts.employee')
@section('title', 'Apply for Leave — Form 6')
@section('page-title', 'Apply for Leave')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">CS Form No. 6 — Application for Leave</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Fill out the form completely before submitting.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger anim-fade-up mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('employee.leave.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ── SECTION 1: Basic Info ─────────────────────────────────────────────── --}}
        <div class="stat-card anim-fade-up delay-1 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                <i class="bi bi-person-fill me-2"></i>Personal Information
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Office/Department <span class="text-danger">*</span></label>
                    <select name="department" class="form-select" required>
                        <option value="" disabled>-- Select Department --</option>
                        @foreach (['Accounting', 'Administrative Office', 'Budget', 'Cash', 'CID', 'EFS', 'Health', 'Legal', 'Library Hub', 'LRMD', 'Office of the ASDS', 'Office of the SDS', 'Personnel', 'Planning', 'Private School', 'Procurement', 'Property and Supply', 'Records', 'Research', 'SGOD'] as $dept)
                            <option value="{{ $dept }}"
                                {{ old('department', $employee?->employment?->school_office_assign) == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control"
                        value="{{ old('last_name', $employee?->last_name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control"
                        value="{{ old('first_name', $employee?->first_name) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control"
                        value="{{ old('middle_name', $employee?->middle_name) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date of Filing <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_filing" class="form-control"
                        value="{{ old('date_of_filing', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Position <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control"
                        value="{{ old('position', Auth::user()->user_pos) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Salary <span class="text-danger">*</span></label>
                    <input type="text" name="salary" class="form-control"
                        value="SG{{ old('salary', $employee?->employment?->salary_grade) }}" required>
                </div>
            </div>
        </div>

        {{-- ── SECTION A: Type of Leave ─────────────────────────────────────────────── --}}
        <div class="stat-card anim-fade-up delay-2 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                <i class="bi bi-list-check me-2"></i>A. Type of Leave to be Availed Of
                <span class="text-danger">*</span>
            </div>
            <div class="row g-2">
                @php
                    $leaveOptions = [
                        'Vacation Leave' => 'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        'Mandatory / Forced Leave' => 'Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        'Sick Leave' => 'Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        'Maternity Leave' => 'R.A. No. 11210 / IRR issued by CSC, DOLE and SSS',
                        'Paternity Leave' => 'R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended',
                        'Special Privilege Leave' => 'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        'Solo Parent Leave' => 'RA No. 8972 / CSC MC No. 8, s. 2004',
                        'Study Leave' => 'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        '10-Day VAWC Leave' => 'RA No. 9262 / CSC MC No. 15, s. 2005',
                        'Rehabilitation Privilege' => 'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                        'Special Leave Benefits for Women' => 'RA No. 9710 / CSC MC No. 25, s. 2010',
                        'Special Emergency' => 'Calamity Leave (CSC MC No. 2, s. 2012, as amended)',
                        'Adoption Leave' => 'R.A. No. 8552',
                    ];
                @endphp

                @foreach ($leaveOptions as $value => $desc)
                    <div class="col-md-6">
                        <div style="padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);
                        transition:all var(--transition);"
                            class="leave-option-item">
                            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;margin:0;">
                                <input type="checkbox" name="leave_types[]" value="{{ $value }}"
                                    style="margin-top:3px;flex-shrink:0;"
                                    {{ in_array($value, old('leave_types', [])) ? 'checked' : '' }}>
                                <span>
                                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);display:block;">
                                        {{ $value }}
                                    </span>
                                    <span style="font-size:11px;color:var(--text-secondary);">{{ $desc }}</span>
                                </span>
                            </label>
                        </div>
                    </div>
                @endforeach

                {{-- Others --}}
                <div class="col-12">
                    <div style="padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin:0;">
                            <input type="checkbox" id="others_check" name="leave_types[]" value="Others">
                            <span style="font-size:13px;font-weight:600;color:var(--text-primary);">Others:</span>
                            <input type="text" id="others_text" name="others_text" class="form-control"
                                style="max-width:300px;" placeholder="Specify..." value="{{ old('others_text') }}"
                                disabled>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECTION B: Details of Leave ─────────────────────────────────────────── --}}
        <div class="stat-card anim-fade-up delay-2 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                <i class="bi bi-card-text me-2"></i>B. Details of Leave
            </div>

            <p style="font-size:12px;font-style:italic;color:var(--text-secondary);margin-bottom:8px;">
                In case of Vacation/Special Privilege Leave:
            </p>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" id="within_ph_check" name="within_ph">
                        Within the Philippines
                    </label>
                    <input type="text" id="within_ph_text" name="within_ph_text" class="form-control mt-1"
                        placeholder="Specify location..." disabled value="{{ old('within_ph_text') }}">
                </div>
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" id="abroad_check" name="abroad">
                        Abroad (Specify)
                    </label>
                    <input type="text" id="abroad_text" name="abroad_text" class="form-control mt-1"
                        placeholder="Specify country..." disabled value="{{ old('abroad_text') }}">
                </div>
            </div>

            <p style="font-size:12px;font-style:italic;color:var(--text-secondary);margin-bottom:8px;">
                In case of Sick Leave:
            </p>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" id="in_hospital_check" name="in_hospital">
                        In Hospital (Specify Illness)
                    </label>
                    <input type="text" id="in_hospital_text" name="in_hospital_text" class="form-control mt-1"
                        placeholder="Specify illness..." disabled value="{{ old('in_hospital_text') }}">
                </div>
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" id="out_patient_check" name="out_patient">
                        Out Patient (Specify Illness)
                    </label>
                    <input type="text" id="out_patient_text" name="out_patient_text" class="form-control mt-1"
                        placeholder="Specify illness..." disabled value="{{ old('out_patient_text') }}">
                </div>
            </div>

            <p style="font-size:12px;font-style:italic;color:var(--text-secondary);margin-bottom:8px;">
                In case of Special Leave Benefits for Women:
            </p>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="special_leave_bw" class="form-control" placeholder="Specify illness..."
                        value="{{ old('special_leave_bw') }}">
                </div>
            </div>

            <p style="font-size:12px;font-style:italic;color:var(--text-secondary);margin-bottom:8px;">
                In case of Study Leave:
            </p>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="completion_masters"
                            {{ old('completion_masters') ? 'checked' : '' }}>
                        Completion of Master's Degree
                    </label>
                </div>
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="bar_board_exam" {{ old('bar_board_exam') ? 'checked' : '' }}>
                        BAR/Board Examination Review
                    </label>
                </div>
            </div>

            <p style="font-size:12px;font-style:italic;color:var(--text-secondary);margin-bottom:8px;">
                Other purpose:
            </p>
            <div class="row g-2">
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="monetization" {{ old('monetization') ? 'checked' : '' }}>
                        Monetization of Leave Credits
                    </label>
                </div>
                <div class="col-md-6">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;">
                        <input type="checkbox" name="terminal_leave" {{ old('terminal_leave') ? 'checked' : '' }}>
                        Terminal Leave
                    </label>
                </div>
            </div>
        </div>

        {{-- ── SECTION C & D: Days + Commutation ───────────────────────────────────── --}}
        <div class="stat-card anim-fade-up delay-3 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                <i class="bi bi-calendar-range me-2"></i>C & D. Duration & Commutation
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">No. of Working Days Applied <span class="text-danger">*</span></label>
                    <input type="text" name="number_of_days" class="form-control" placeholder="e.g. 3"
                        value="{{ old('number_of_days') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Inclusive Dates <span class="text-danger">*</span></label>
                    <input type="text" name="inclusive_dates" class="form-control" placeholder="e.g. Jan 1-3, 2025"
                        value="{{ old('inclusive_dates') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                        min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"
                        min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Commutation <span class="text-danger">*</span></label>
                    <div style="display:flex;gap:20px;margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                            <input type="radio" name="commutation" value="Not Required"
                                {{ old('commutation') == 'Not Required' ? 'checked' : '' }} required>
                            Not Required
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                            <input type="radio" name="commutation" value="Required"
                                {{ old('commutation') == 'Required' ? 'checked' : '' }}>
                            Required
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECTION: Attachment & Remarks ───────────────────────────────────────── --}}
        <div class="stat-card anim-fade-up delay-3 mb-3">
            <div
                style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;
                letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                <i class="bi bi-paperclip me-2"></i>Supporting Documents & Remarks
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="leavefile" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Medical certificate or supporting document. Max 4MB.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" maxlength="100" placeholder="Optional note...">{{ old('remarks') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Approval Flow Info ───────────────────────────────────────────────────── --}}
        <div class="anim-fade-up delay-4 mb-4"
            style="background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.2);
            border-radius:var(--radius-sm);padding:14px 18px;">
            <div style="font-size:12px;font-weight:700;color:#1D4ED8;margin-bottom:6px;">
                <i class="bi bi-info-circle me-1"></i> Approval Flow — Non-Teaching
            </div>
            <div style="font-size:12px;color:var(--text-secondary);line-height:2;">
                <span style="font-weight:600;">You</span> →
                <span style="font-weight:600;">Department Head</span> (Endorsement) →
                <span style="font-weight:600;">HR</span> (Leave Credits) →
                <span style="font-weight:600;">Administrative Officer</span> (Approval) →
                <span style="font-weight:600;">ASDS</span> (Final Approval) →
                <span style="color:#059669;font-weight:600;">✅ Email Sent</span>
            </div>
        </div>

        {{-- ── Buttons ──────────────────────────────────────────────────────────────── --}}
        <div class="d-flex gap-2 anim-fade-up delay-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send me-2"></i> Submit Form 6
            </button>
            <a href="{{ route('employee.leave.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>

    </form>

    @push('scripts')
        <script>
            // Others checkbox toggle
            document.getElementById('others_check').addEventListener('change', function() {
                document.getElementById('others_text').disabled = !this.checked;
                if (!this.checked) document.getElementById('others_text').value = '';
            });

            // Detail field toggles
            const togglePairs = [
                ['within_ph_check', 'within_ph_text'],
                ['abroad_check', 'abroad_text'],
                ['in_hospital_check', 'in_hospital_text'],
                ['out_patient_check', 'out_patient_text'],
            ];
            togglePairs.forEach(([checkId, inputId]) => {
                const check = document.getElementById(checkId);
                const input = document.getElementById(inputId);
                if (check && input) {
                    check.addEventListener('change', function() {
                        input.disabled = !this.checked;
                        if (!this.checked) input.value = '';
                    });
                }
            });

            // Highlight selected leave type cards
            document.querySelectorAll('.leave-option-item input[type=checkbox]').forEach(cb => {
                cb.addEventListener('change', function() {
                    this.closest('.leave-option-item').style.background =
                        this.checked ? 'rgba(52,211,153,0.06)' : '';
                    this.closest('.leave-option-item').style.borderColor =
                        this.checked ? 'rgba(52,211,153,0.4)' : 'var(--border)';
                });
            });
        </script>
    @endpush

@endsection

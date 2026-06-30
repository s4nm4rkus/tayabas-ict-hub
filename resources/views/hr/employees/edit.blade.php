@extends('layouts.hr')
@section('title', 'Edit — ' . $employee->full_name)
@section('page-title', 'Edit Employee')

@section('content')

    {{-- Header --}}
    <div class="anim-fade-up mb-4"
        style="background:var(--surface);border-radius:var(--radius);
               border:1px solid var(--border);padding:1.5rem;box-shadow:var(--shadow-sm);">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            @if ($employee->photo_path)
                <img src="{{ asset('storage/' . $employee->photo_path) }}"
                    style="width:60px;height:60px;border-radius:50%;object-fit:cover;
                           border:3px solid rgba(52,211,153,0.3);">
            @else
                <div
                    style="width:60px;height:60px;border-radius:50%;flex-shrink:0;
                            background:linear-gradient(135deg,#34D399,#059669);
                            color:#fff;display:flex;align-items:center;justify-content:center;
                            font-size:22px;font-weight:700;">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-grow-1">
                <h5 style="margin:0;font-size:17px;font-weight:700;color:var(--text-primary);">
                    {{ $employee->full_name }}
                </h5>
                <span class="status-badge badge-gray mt-1">{{ $employee->user?->user_id ?? '—' }}</span>
            </div>
            <a href="{{ route('hr.employees.show', $employee->user_id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Please fix these errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li style="font-size:13.5px;">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs --}}
    <ul class="emp-tabs anim-fade-up delay-1 mb-4" id="editTabs">
        <li><a href="#tab-personal" class="tab-link active">Personal</a></li>
        <li><a href="#tab-employment" class="tab-link">Employment</a></li>
        <li><a href="#tab-education" class="tab-link">Education</a></li>
        <li><a href="#tab-eligibility" class="tab-link">Eligibility</a></li>
        <li><a href="#tab-service" class="tab-link">Service Records</a></li>
    </ul>

    {{-- ══════════════════════════════════════════════════════
         MAIN FORM — Personal, Employment, Education, Eligibility
         Service Records tab is display-only inside here (no sub-forms)
         ══════════════════════════════════════════════════════ --}}
    <form method="POST" action="{{ route('hr.employees.update', $employee->user_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Personal Tab --}}
        <div class="tab-panel active" id="tab-personal">
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-person"></i> Personal Information</div>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $employee->last_name) }}" required>
                    </div>
                    <div class="col-md-3"><label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $employee->first_name) }}" required>
                    </div>
                    <div class="col-md-3"><label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                            value="{{ old('middle_name', $employee->middle_name) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Extension</label>
                        <input type="text" name="ex_name" class="form-control"
                            value="{{ old('ex_name', $employee->ex_name) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>
                                Female</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Birthdate <span class="text-danger">*</span></label>
                        <input type="date" name="birthdate" class="form-control"
                            value="{{ old('birthdate', $employee->birthdate?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3"><label class="form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control"
                            value="{{ old('place_of_birth', $employee->place_of_birth) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Contact Number</label>
                        <input type="text" name="contact_num" class="form-control"
                            value="{{ old('contact_num', $employee->contact_num) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">BP No.</label>
                        <input type="text" name="bp_no" class="form-control"
                            value="{{ old('bp_no', $employee->bp_no) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Disability</label>
                        <input type="text" name="disability" class="form-control"
                            value="{{ old('disability', $employee->disability) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Update Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-card-list"></i> Government IDs</div>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Employee No.</label>
                        <input type="text" name="employee_no" class="form-control"
                            value="{{ old('employee_no', $employee->employee_no) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">PhilHealth</label>
                        <input type="text" name="philhealth" class="form-control"
                            value="{{ old('philhealth', $employee->philhealth) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Pag-IBIG</label>
                        <input type="text" name="pagibig" class="form-control"
                            value="{{ old('pagibig', $employee->pagibig) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">TIN</label>
                        <input type="text" name="TIN" class="form-control"
                            value="{{ old('TIN', $employee->TIN) }}">
                    </div>
                </div>
            </div>
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-geo-alt"></i> Address</div>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Street</label>
                        <input type="text" name="street" class="form-control"
                            value="{{ old('street', $employee->street) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Barangay</label>
                        <input type="text" name="street_brgy" class="form-control"
                            value="{{ old('street_brgy', $employee->street_brgy) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Municipality</label>
                        <input type="text" name="municipality" class="form-control"
                            value="{{ old('municipality', $employee->municipality) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Province</label>
                        <input type="text" name="province" class="form-control"
                            value="{{ old('province', $employee->province) }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">Region</label>
                        <input type="text" name="region" class="form-control"
                            value="{{ old('region', $employee->region) }}">
                    </div>
                </div>
            </div>
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-shield-lock"></i> Account</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Government Email</label>
                        <input type="email" name="gov_email" class="form-control"
                            value="{{ old('gov_email', $employee->gov_email) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Position / Role</label>
                        <select name="user_pos" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_desc }}"
                                    {{ old('user_pos', $employee->user?->user_pos) == $role->role_desc ? 'selected' : '' }}>
                                    {{ $role->role_desc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0" style="font-size:13px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Account status can only be changed by the System Administrator.
                    Current status: <strong>{{ $employee->user?->user_stat }}</strong>
                </div>
            </div>
        </div>

        {{-- Employment Tab --}}
        <div class="tab-panel" id="tab-employment">
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-briefcase"></i> Employment Details</div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Designation (if any)</label>
                        <select name="sub_position" class="form-select">
                            <option value="">Select Designation (if any)</option>
                            @foreach ($subPositions as $sub)
                                <option value="{{ $sub->sub_position }}"
                                    {{ old('sub_position', $employee->employment?->sub_position) == $sub->sub_position ? 'selected' : '' }}>
                                    {{ $sub->sub_position }}{{ $sub->main_pos ? ' (' . $sub->main_pos . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Nature of Appointment</label>
                        <select name="nature_appoint" class="form-select">
                            <option value="">— Select Nature —</option>
                            @foreach ($natureOptions as $opt)
                                <option value="{{ $opt->option_value }}"
                                    {{ old('nature_appoint', $employee->employment?->nature_appoint) === $opt->option_value ? 'selected' : '' }}>
                                    {{ $opt->option_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Status of Appointment</label>
                        <select name="status_appoint" class="form-select">
                            <option value="">— Select Status —</option>
                            @foreach ($statusOptions as $opt)
                                <option value="{{ $opt->option_value }}"
                                    {{ old('status_appoint', $employee->employment?->status_appoint) === $opt->option_value ? 'selected' : '' }}>
                                    {{ $opt->option_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Original Appointment Date</label>
                        <input type="date" name="date_orig_appoint" class="form-control"
                            value="{{ old('date_orig_appoint', $employee->employment?->date_orig_appoint) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Station Code</label>
                        <input type="text" name="station_code" class="form-control"
                            value="{{ old('station_code', $employee->employment?->station_code) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Salary Grade</label>
                        <select name="salary_grade" class="form-select">
                            <option value="">Select Grade</option>
                            @foreach ($salaryGrades as $sg)
                                <option value="{{ $sg->salary_grade }}"
                                    {{ old('salary_grade', $employee->employment?->salary_grade) == $sg->salary_grade ? 'selected' : '' }}>
                                    SG {{ $sg->salary_grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Salary Step</label>
                        <select name="salary_step" class="form-select">
                            <option value="">Select Step</option>
                            @foreach (range(1, 8) as $step)
                                <option value="{{ $step }}"
                                    {{ old('salary_step', $employee->employment?->salary_step) == $step ? 'selected' : '' }}>
                                    Step {{ $step }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Salary Effectivity Date</label>
                        <input type="date" name="salary_effect_date" class="form-control"
                            value="{{ old('salary_effect_date', $employee->employment?->salary_effect_date) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Plantilla Item No.</label>
                        <input type="text" name="plantilla_item_no" class="form-control"
                            value="{{ old('plantilla_item_no', $employee->employment?->plantilla_item_no) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">School/Office Assigned</label>
                        <input type="text" name="school_office_assign" class="form-control"
                            value="{{ old('school_office_assign', $employee->employment?->school_office_assign) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Detailed Assignment</label>
                        <input type="text" name="school_detailed_office_assign" class="form-control"
                            value="{{ old('school_detailed_office_assign', $employee->employment?->school_detailed_office_assign) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Vice</label>
                        <input type="text" name="vice" class="form-control"
                            value="{{ old('vice', $employee->employment?->vice) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Vice Reason</label>
                        <input type="text" name="vice_reason" class="form-control"
                            value="{{ old('vice_reason', $employee->employment?->vice_reason) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Designated From</label>
                        <input type="date" name="designated_from" class="form-control"
                            value="{{ old('designated_from', $employee->employment?->designated_from) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Designated To</label>
                        <input type="date" name="designated_to" class="form-control"
                            value="{{ old('designated_to', $employee->employment?->designated_to) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Separation</label>
                        <input type="text" name="separation" class="form-control"
                            value="{{ old('separation', $employee->employment?->separation) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Separation Date</label>
                        <input type="date" name="separation_date" class="form-control"
                            value="{{ old('separation_date', $employee->employment?->separation_date) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Education Tab --}}
        <div class="tab-panel" id="tab-education">
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-mortarboard"></i> Educational Background</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Elementary School</label>
                        <input type="text" name="elementary" class="form-control"
                            value="{{ old('elementary', $employee->education?->elementary) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Elementary Duration</label>
                        <input type="text" name="elem_duration" class="form-control"
                            value="{{ old('elem_duration', $employee->education?->elem_duration) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Secondary School</label>
                        <input type="text" name="secondary" class="form-control"
                            value="{{ old('secondary', $employee->education?->secondary) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Secondary Duration</label>
                        <input type="text" name="second_duration" class="form-control"
                            value="{{ old('second_duration', $employee->education?->second_duration) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">College Course</label>
                        <input type="text" name="college" class="form-control"
                            value="{{ old('college', $employee->education?->college) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">College School</label>
                        <input type="text" name="college_school" class="form-control"
                            value="{{ old('college_school', $employee->education?->college_school) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">College Duration</label>
                        <input type="text" name="college_duration" class="form-control"
                            value="{{ old('college_duration', $employee->education?->college_duration) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Masters Degree</label>
                        <input type="text" name="masters_degree" class="form-control"
                            value="{{ old('masters_degree', $employee->education?->masters_degree) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Masters Duration</label>
                        <input type="text" name="master_duration" class="form-control"
                            value="{{ old('master_duration', $employee->education?->master_duration) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Masters Units</label>
                        <input type="text" name="master_units" class="form-control"
                            value="{{ old('master_units', $employee->education?->master_units) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Doctorate Degree</label>
                        <input type="text" name="doc_degree" class="form-control"
                            value="{{ old('doc_degree', $employee->education?->doc_degree) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Doctorate Duration</label>
                        <input type="text" name="doc_duration" class="form-control"
                            value="{{ old('doc_duration', $employee->education?->doc_duration) }}">
                    </div>
                    <div class="col-md-6"><label class="form-label">Doctorate Units</label>
                        <input type="text" name="doc_units" class="form-control"
                            value="{{ old('doc_units', $employee->education?->doc_units) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Eligibility Tab --}}
        <div class="tab-panel" id="tab-eligibility">
            <div class="edit-section mb-3">
                <div class="edit-section-title"><i class="bi bi-patch-check"></i> Eligibility</div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Type of Eligibility</label>
                        <input type="text" name="type_eligibility" class="form-control"
                            value="{{ old('type_eligibility', $employee->eligibility?->type_eligibility) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Date Issued</label>
                        <input type="date" name="date_issue" class="form-control"
                            value="{{ old('date_issue', $employee->eligibility?->date_issue?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4"><label class="form-label">Validity</label>
                        <input type="text" name="validity" class="form-control"
                            value="{{ old('validity', $employee->eligibility?->validity) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Records Tab — display only, no sub-forms here --}}
        <div class="tab-panel" id="tab-service">

            {{-- Service Records Table --}}
            <div class="edit-section mb-3">
                <div class="edit-section-title">
                    <i class="bi bi-journal-text"></i> Service Records
                    <div style="margin-left:auto;display:flex;gap:8px;">
                        <button type="button" onclick="openRecordChangeModal()" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-up-circle me-1"></i> Record Change
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addServiceModal">
                            <i class="bi bi-plus me-1"></i> Add Manual Record
                        </button>
                    </div>
                </div>

                @if ($employee->serviceRecords->count())
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:13px;">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Position</th>
                                    <th>Station</th>
                                    <th>SG / Step</th>
                                    <th>Status</th>
                                    <th style="width:60px;text-align:center;">Auto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee->serviceRecords->sortBy('inclu_from') as $rec)
                                    <tr>
                                        <td style="white-space:nowrap;">{{ $rec->inclu_from?->format('M d, Y') ?? '—' }}
                                        </td>
                                        <td style="white-space:nowrap;">
                                            {{ $rec->inclu_to?->format('M d, Y') ?? 'To Date' }}</td>
                                        <td style="font-weight:500;">{{ $rec->position ?? '—' }}</td>
                                        <td>{{ $rec->station ?? '—' }}</td>
                                        <td>
                                            @if ($rec->salary_grade)
                                                <span style="font-size:12px;">SG {{ $rec->salary_grade }}, Step
                                                    {{ $rec->salary_step }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge badge-info">{{ $rec->service_status ?? '—' }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($rec->is_auto_generated)
                                                <i class="bi bi-cpu" style="color:#059669;font-size:13px;"
                                                    title="Auto-generated"></i>
                                            @else
                                                <i class="bi bi-pencil"
                                                    style="color:var(--text-secondary);font-size:13px;"
                                                    title="Manual"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align:center;padding:2rem;color:var(--text-secondary);">
                        <i class="bi bi-journal-x"
                            style="font-size:28px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                        <div style="font-size:13px;">No service records yet.</div>
                        <div style="font-size:12px;margin-top:4px;opacity:0.7;">Save employment info to auto-generate
                            records.</div>
                    </div>
                @endif
            </div>

            {{-- Employment History Timeline — inside tab-service, outside main form --}}
            {{-- NOTE: rendered after </form> tag below, toggled into view via JS --}}

        </div>

        {{-- Save Button --}}
        <div class="d-flex gap-2 mt-3 mb-3 anim-fade-up">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-2"></i> Save Changes
            </button>
            <a href="{{ route('hr.employees.show', $employee->user_id) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>

    </form>
    {{-- ══ END MAIN FORM ══ --}}

    {{-- ══════════════════════════════════════════════════════
         EMPLOYMENT HISTORY TIMELINE
         Outside main form to allow nested DELETE forms.
         Visually shown inside the Service Records tab via JS.
         ══════════════════════════════════════════════════════ --}}
    <div id="employment-history-section" style="display:none;">
        @if ($employee->employmentHistories->count() > 0)
            <div class="edit-section mb-3 mt-0">
                <div class="edit-section-title">
                    <i class="bi bi-clock-history"></i> Employment History
                    <span
                        style="font-size:11px;font-weight:500;color:var(--text-secondary);
                          background:var(--bg);padding:2px 8px;border-radius:99px;
                          border:1px solid var(--border);margin-left:6px;">
                        {{ $employee->employmentHistories->count() }} entries
                    </span>
                    <a href="{{ route('hr.employees.history.index', $employee->user_id) }}"
                        class="btn btn-outline-secondary btn-sm ms-auto" style="font-size:11px;">
                        <i class="bi bi-arrows-fullscreen me-1"></i> Full View
                    </a>
                </div>

                <div style="position:relative;padding-left:24px;">
                    <div
                        style="position:absolute;left:7px;top:8px;bottom:8px;width:2px;
                         background:var(--border);border-radius:2px;">
                    </div>

                    @foreach ($employee->employmentHistories->sortByDesc('effective_date') as $hist)
                        @php
                            $badgeColor = match ($hist->change_reason) {
                                'PROMOTION' => [
                                    'bg' => 'rgba(52,211,153,0.12)',
                                    'color' => '#059669',
                                    'border' => 'rgba(52,211,153,0.3)',
                                ],
                                'DEMOTION' => [
                                    'bg' => 'rgba(239,68,68,0.08)',
                                    'color' => '#B91C1C',
                                    'border' => 'rgba(239,68,68,0.2)',
                                ],
                                'TRANSFER' => [
                                    'bg' => 'rgba(74,144,226,0.1)',
                                    'color' => '#2563EB',
                                    'border' => 'rgba(74,144,226,0.25)',
                                ],
                                'RECLASSIFICATION' => [
                                    'bg' => 'rgba(245,158,11,0.1)',
                                    'color' => '#B45309',
                                    'border' => 'rgba(245,158,11,0.25)',
                                ],
                                default => [
                                    'bg' => 'rgba(139,92,246,0.1)',
                                    'color' => '#7C3AED',
                                    'border' => 'rgba(139,92,246,0.25)',
                                ],
                            };
                        @endphp
                        <div style="position:relative;margin-bottom:12px;">
                            <div
                                style="position:absolute;left:-21px;top:14px;width:10px;height:10px;border-radius:50%;
                                 background:{{ is_null($hist->end_date) ? '#34D399' : 'var(--border)' }};
                                 border:2px solid {{ is_null($hist->end_date) ? '#059669' : 'var(--text-secondary)' }};z-index:1;">
                            </div>

                            <div
                                style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px;">
                                <div
                                    style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                                    <div>
                                        <span
                                            style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;
                                              background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['color'] }};
                                              border:1px solid {{ $badgeColor['border'] }};letter-spacing:0.05em;">
                                            {{ $hist->change_reason }}
                                        </span>
                                        <div
                                            style="font-size:13px;font-weight:700;color:var(--text-primary);margin-top:6px;">
                                            {{ $hist->position }}
                                            @if ($hist->sub_position)
                                                <span style="font-weight:400;color:var(--text-secondary);font-size:12px;">—
                                                    {{ $hist->sub_position }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                                            SG {{ $hist->salary_grade }}, Step {{ $hist->salary_step }}
                                            &nbsp;·&nbsp; {{ $hist->status_appoint ?? '—' }}
                                        </div>
                                    </div>
                                    <div style="text-align:right;flex-shrink:0;">
                                        <div style="font-size:12px;font-weight:600;color:var(--text-primary);">
                                            {{ \Carbon\Carbon::parse($hist->effective_date)->format('M d, Y') }}
                                        </div>
                                        <div style="font-size:11px;color:var(--text-secondary);">
                                            @if (is_null($hist->end_date))
                                                <span style="color:#059669;font-weight:600;">● Current</span>
                                            @else
                                                to {{ \Carbon\Carbon::parse($hist->end_date)->format('M d, Y') }}
                                            @endif
                                        </div>
                                        @if ($hist->change_reason !== 'ORIGINAL')
                                            <form method="POST"
                                                action="{{ route('hr.employees.history.destroy', ['userId' => $employee->user_id, 'historyId' => $hist->id]) }}"
                                                onsubmit="return confirm('Remove this entry? Service records will be recalculated.')"
                                                style="margin-top:6px;">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    style="font-size:11px;padding:2px 8px;border-radius:5px;
                                                    background:rgba(239,68,68,0.08);color:#B91C1C;
                                                    border:1px solid rgba(239,68,68,0.15);cursor:pointer;">
                                                    <i class="bi bi-trash" style="font-size:10px;"></i> Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div
                                    style="font-size:11px;color:var(--text-secondary);margin-top:6px;padding-top:6px;border-top:1px solid var(--border);">
                                    <i class="bi bi-calendar3" style="font-size:10px;"></i>
                                    Step anchor: {{ \Carbon\Carbon::parse($hist->step_anchor)->format('M d, Y') }}
                                    &nbsp;·&nbsp; Station: {{ $hist->station ?? '—' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Add Service Record Modal — outside main form --}}
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);">
                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                    <h5 class="modal-title" style="font-size:15px;font-weight:700;">Add Service Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('hr.employees.service.store', $employee->user_id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">From</label>
                                <input type="date" name="inclu_from" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">To</label>
                                <input type="date" name="inclu_to" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">Station</label>
                                <input type="text" name="station" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">Branch</label>
                                <input type="text" name="branch" class="form-control">
                            </div>
                            <div class="col-md-4"><label class="form-label">Salary Grade</label>
                                <input type="text" name="salary_grade" class="form-control">
                            </div>
                            <div class="col-md-4"><label class="form-label">Salary Step</label>
                                <input type="text" name="salary_step" class="form-control">
                            </div>
                            <div class="col-md-4"><label class="form-label">Service Status</label>
                                <input type="text" name="service_status" class="form-control">
                            </div>
                            <div class="col-md-6"><label class="form-label">Separation</label>
                                <input type="text" name="separation" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--border);">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus me-1"></i> Add Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .emp-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 2px;
            flex-wrap: wrap;
            border-bottom: 2px solid var(--border);
        }

        .emp-tabs li {
            margin-bottom: -2px;
        }

        .tab-link {
            display: block;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all var(--transition);
            border-radius: 6px 6px 0 0;
        }

        .tab-link:hover {
            color: var(--text-primary);
            background: rgba(52, 211, 153, 0.05);
        }

        .tab-link.active {
            color: #059669;
            border-bottom-color: #059669;
            background: rgba(52, 211, 153, 0.06);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .edit-section {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.375rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .edit-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    @push('scripts')
        <script>
            // Tab switching — show/hide history section when Service tab is active
            document.querySelectorAll('.tab-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    const target = this.getAttribute('href');
                    document.querySelector(target).classList.add('active');

                    // Show employment history section only when on service tab
                    const historySection = document.getElementById('employment-history-section');
                    if (historySection) {
                        historySection.style.display = target === '#tab-service' ? 'block' : 'none';
                    }
                });
            });
        </script>
    @endpush

    @include('hr.employees.partials.record-change-modal', [
        'employee' => $employee,
        'natureOptions' => $natureOptions,
        'statusOptions' => $statusOptions,
        'salaryGrades' => $salaryGrades,
        'subPositions' => $subPositions,
    ])

@endsection

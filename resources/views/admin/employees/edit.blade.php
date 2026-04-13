@extends('layouts.admin')

@section('title', 'Edit — ' . $employee->full_name)
@section('page-title', 'Edit Employee')

@section('content')

    <div class="profile-header mb-4">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="profile-avatar">
                @if ($employee->photo_path)
                    <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Photo">
                @else
                    <span>{{ strtoupper(substr($employee->first_name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <h5 class="mb-1">{{ $employee->full_name }}</h5>
                <span class="profile-badge gray">{{ $employee->user?->user_id ?? '—' }}</span>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.employees.show', $employee->user_id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <ul class="profile-tabs mb-4" id="editTabs">
        <li><a href="#tab-personal" class="tab-link active">Personal</a></li>
        <li><a href="#tab-employment" class="tab-link">Employment</a></li>
        <li><a href="#tab-education" class="tab-link">Education</a></li>
        <li><a href="#tab-eligibility" class="tab-link">Eligibility</a></li>
        <li><a href="#tab-service" class="tab-link">Service Records</a></li>
    </ul>

    <form method="POST" action="{{ route('admin.employees.update', $employee->user_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── Personal Tab ── --}}
        <div class="tab-panel active" id="tab-personal">

            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-person"></i> Personal Information</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $employee->last_name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $employee->first_name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                            value="{{ old('middle_name', $employee->middle_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Extension</label>
                        <input type="text" name="ex_name" class="form-control"
                            value="{{ old('ex_name', $employee->ex_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>
                                Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                        <input type="date" name="birthdate" class="form-control"
                            value="{{ old('birthdate', $employee->birthdate?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control"
                            value="{{ old('place_of_birth', $employee->place_of_birth) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_num" class="form-control"
                            value="{{ old('contact_num', $employee->contact_num) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">BP No.</label>
                        <input type="text" name="bp_no" class="form-control"
                            value="{{ old('bp_no', $employee->bp_no) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Disability</label>
                        <input type="text" name="disability" class="form-control"
                            value="{{ old('disability', $employee->disability) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Update Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-card-list"></i> Government IDs</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Employee No.</label>
                        <input type="text" name="employee_no" class="form-control"
                            value="{{ old('employee_no', $employee->employee_no) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">PhilHealth</label>
                        <input type="text" name="philhealth" class="form-control"
                            value="{{ old('philhealth', $employee->philhealth) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pag-IBIG</label>
                        <input type="text" name="pagibig" class="form-control"
                            value="{{ old('pagibig', $employee->pagibig) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">TIN</label>
                        <input type="text" name="TIN" class="form-control"
                            value="{{ old('TIN', $employee->TIN) }}">
                    </div>
                </div>
            </div>

            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-geo-alt"></i> Address</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Street</label>
                        <input type="text" name="street" class="form-control"
                            value="{{ old('street', $employee->street) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Barangay</label>
                        <input type="text" name="street_brgy" class="form-control"
                            value="{{ old('street_brgy', $employee->street_brgy) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Municipality</label>
                        <input type="text" name="municipality" class="form-control"
                            value="{{ old('municipality', $employee->municipality) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Province</label>
                        <input type="text" name="province" class="form-control"
                            value="{{ old('province', $employee->province) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Region</label>
                        <input type="text" name="region" class="form-control"
                            value="{{ old('region', $employee->region) }}">
                    </div>
                </div>
            </div>

            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-shield-lock"></i> Account</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Government Email</label>
                        <input type="email" name="gov_email" class="form-control"
                            value="{{ old('gov_email', $employee->gov_email) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Position / Role</label>
                        <select name="user_pos" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_desc }}"
                                    {{ old('user_pos', $employee->user?->user_pos) == $role->role_desc ? 'selected' : '' }}>
                                    {{ $role->role_desc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Status</label>
                        <select name="user_stat" class="form-select">
                            <option value="Enabled" {{ $employee->user?->user_stat == 'Enabled' ? 'selected' : '' }}>
                                Enabled</option>
                            <option value="Disabled" {{ $employee->user?->user_stat == 'Disabled' ? 'selected' : '' }}>
                                Disabled</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Employment Tab ── --}}
        <div class="tab-panel" id="tab-employment">
            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-briefcase"></i> Employment Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Sub Position</label>
                        <select name="sub_position" class="form-select">
                            <option value="">Select Sub Position</option>
                            @foreach ($subPositions as $sub)
                                <option value="{{ $sub->sub_position }}"
                                    {{ old('sub_position', $employee->employment?->sub_position) == $sub->sub_position ? 'selected' : '' }}>
                                    {{ $sub->sub_position }}
                                    @if ($sub->main_pos)
                                        ({{ $sub->main_pos }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nature of Appointment</label>
                        <input type="text" name="nature_appoint" class="form-control"
                            value="{{ old('nature_appoint', $employee->employment?->nature_appoint) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status of Appointment</label>
                        <input type="text" name="status_appoint" class="form-control"
                            value="{{ old('status_appoint', $employee->employment?->status_appoint) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Original Appointment Date</label>
                        <input type="date" name="date_orig_appoint" class="form-control"
                            value="{{ old('date_orig_appoint', $employee->employment?->date_orig_appoint) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Salary Grade</label>
                        <select name="salary_grade" class="form-select" id="salary_grade_select">
                            <option value="">Select Grade</option>
                            @foreach ($salaryGrades as $sg)
                                <option value="{{ $sg->salary_grade }}"
                                    {{ old('salary_grade', $employee->employment?->salary_grade) == $sg->salary_grade ? 'selected' : '' }}>
                                    SG {{ $sg->salary_grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Salary Step</label>
                        <select name="salary_step" class="form-select" id="salary_step_select">
                            <option value="">Select Step</option>
                            @foreach (range(1, 8) as $step)
                                <option value="{{ $step }}"
                                    {{ old('salary_step', $employee->employment?->salary_step) == $step ? 'selected' : '' }}>
                                    Step {{ $step }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Salary Effectivity Date</label>
                        <input type="date" name="salary_effect_date" class="form-control"
                            value="{{ old('salary_effect_date', $employee->employment?->salary_effect_date) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Plantilla Item No.</label>
                        <input type="text" name="plantilla_item_no" class="form-control"
                            value="{{ old('plantilla_item_no', $employee->employment?->plantilla_item_no) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Plantilla Inclusion</label>
                        <input type="text" name="plantilla_inclu" class="form-control"
                            value="{{ old('plantilla_inclu', $employee->employment?->plantilla_inclu) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">School/Office Assigned</label>
                        <input type="text" name="school_office_assign" class="form-control"
                            value="{{ old('school_office_assign', $employee->employment?->school_office_assign) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Detailed Assignment</label>
                        <input type="text" name="school_detailed_office_assign" class="form-control"
                            value="{{ old('school_detailed_office_assign', $employee->employment?->school_detailed_office_assign) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vice</label>
                        <input type="text" name="vice" class="form-control"
                            value="{{ old('vice', $employee->employment?->vice) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vice Reason</label>
                        <input type="text" name="vice_reason" class="form-control"
                            value="{{ old('vice_reason', $employee->employment?->vice_reason) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designated From</label>
                        <input type="date" name="designated_from" class="form-control"
                            value="{{ old('designated_from', $employee->employment?->designated_from) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designated To</label>
                        <input type="date" name="designated_to" class="form-control"
                            value="{{ old('designated_to', $employee->employment?->designated_to) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Separation</label>
                        <input type="text" name="separation" class="form-control"
                            value="{{ old('separation', $employee->employment?->separation) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Separation Date</label>
                        <input type="date" name="separation_date" class="form-control"
                            value="{{ old('separation_date', $employee->employment?->separation_date) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Education Tab ── --}}
        <div class="tab-panel" id="tab-education">
            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-mortarboard"></i> Educational Background</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Elementary School</label>
                        <input type="text" name="elementary" class="form-control"
                            value="{{ old('elementary', $employee->education?->elementary) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Elementary Duration</label>
                        <input type="text" name="elem_duration" class="form-control"
                            value="{{ old('elem_duration', $employee->education?->elem_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secondary School</label>
                        <input type="text" name="secondary" class="form-control"
                            value="{{ old('secondary', $employee->education?->secondary) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secondary Duration</label>
                        <input type="text" name="second_duration" class="form-control"
                            value="{{ old('second_duration', $employee->education?->second_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">College Course</label>
                        <input type="text" name="college" class="form-control"
                            value="{{ old('college', $employee->education?->college) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">College School</label>
                        <input type="text" name="college_school" class="form-control"
                            value="{{ old('college_school', $employee->education?->college_school) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">College Duration</label>
                        <input type="text" name="college_duration" class="form-control"
                            value="{{ old('college_duration', $employee->education?->college_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vocational Course</label>
                        <input type="text" name="vocational" class="form-control"
                            value="{{ old('vocational', $employee->education?->vocational) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vocational School</label>
                        <input type="text" name="voc_school" class="form-control"
                            value="{{ old('voc_school', $employee->education?->voc_school) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vocational Duration</label>
                        <input type="text" name="voca_duration" class="form-control"
                            value="{{ old('voca_duration', $employee->education?->voca_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Masters Degree</label>
                        <input type="text" name="masters_degree" class="form-control"
                            value="{{ old('masters_degree', $employee->education?->masters_degree) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Masters Duration</label>
                        <input type="text" name="master_duration" class="form-control"
                            value="{{ old('master_duration', $employee->education?->master_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Masters Units</label>
                        <input type="text" name="master_units" class="form-control"
                            value="{{ old('master_units', $employee->education?->master_units) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctorate Degree</label>
                        <input type="text" name="doc_degree" class="form-control"
                            value="{{ old('doc_degree', $employee->education?->doc_degree) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctorate Duration</label>
                        <input type="text" name="doc_duration" class="form-control"
                            value="{{ old('doc_duration', $employee->education?->doc_duration) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctorate Units</label>
                        <input type="text" name="doc_units" class="form-control"
                            value="{{ old('doc_units', $employee->education?->doc_units) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Eligibility Tab ── --}}
        <div class="tab-panel" id="tab-eligibility">
            <div class="info-card mb-3">
                <div class="info-card-title"><i class="bi bi-patch-check"></i> Eligibility</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Type of Eligibility</label>
                        <input type="text" name="type_eligibility" class="form-control"
                            value="{{ old('type_eligibility', $employee->eligibility?->type_eligibility) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date Issued</label>
                        <input type="date" name="date_issue" class="form-control"
                            value="{{ old('date_issue', $employee->eligibility?->date_issue?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Validity</label>
                        <input type="text" name="validity" class="form-control"
                            value="{{ old('validity', $employee->eligibility?->validity) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Service Records Tab ── --}}
        <div class="tab-panel" id="tab-service">
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="bi bi-journal-text"></i> Service Records
                    <button type="button" class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addServiceModal">
                        <i class="bi bi-plus me-1"></i> Add Record
                    </button>
                </div>

                @if ($employee->serviceRecords->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size:14px;">
                            <thead>
                                <tr style="font-size:13px;color:#8892a4;">
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Position</th>
                                    <th>Station</th>
                                    <th>Salary Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee->serviceRecords as $rec)
                                    <tr>
                                        <td>{{ $rec->inclu_from?->format('M d, Y') ?? '—' }}</td>
                                        <td>{{ $rec->inclu_to?->format('M d, Y') ?? '—' }}</td>
                                        <td>{{ $rec->position ?? '—' }}</td>
                                        <td>{{ $rec->station ?? '—' }}</td>
                                        <td>{{ $rec->salary_grade ?? '—' }}</td>
                                        <td>{{ $rec->service_status ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0" style="font-size:14px;">No service records yet.</p>
                @endif
            </div>
        </div>

        {{-- Save Button -- shows on all tabs --}}
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
            <a href="{{ route('admin.employees.show', $employee->user_id) }}"
                class="btn btn-outline-secondary">Cancel</a>
        </div>

    </form>

    {{-- Add Service Record Modal --}}
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:15px;">Add Service Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.employees.service.store', $employee->user_id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">From</label>
                                <input type="date" name="inclu_from" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">To</label>
                                <input type="date" name="inclu_to" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Station</label>
                                <input type="text" name="station" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Branch</label>
                                <input type="text" name="branch" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Salary Grade</label>
                                <input type="text" name="salary_grade" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Salary Step</label>
                                <input type="text" name="salary_step" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Service Status</label>
                                <input type="text" name="service_status" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Separation</label>
                                <input type="text" name="separation" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
        .profile-header {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #e8f0fe;
            color: #4f8ef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-badge {
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 99px;
        }

        .profile-badge.gray {
            background: #f1f1f1;
            color: #555;
        }

        .profile-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            border-bottom: 2px solid #e9ecef;
        }

        .profile-tabs li {
            margin-bottom: -2px;
        }

        .tab-link {
            display: block;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #8892a4;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            border-radius: 4px 4px 0 0;
        }

        .tab-link:hover {
            color: #1a1f2e;
        }

        .tab-link.active {
            color: #4f8ef7;
            border-bottom-color: #4f8ef7;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .info-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    @push('scripts')
        <script>
            document.querySelectorAll('.tab-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelector(this.getAttribute('href')).classList.add('active');
                });
            });
        </script>
    @endpush

@endsection

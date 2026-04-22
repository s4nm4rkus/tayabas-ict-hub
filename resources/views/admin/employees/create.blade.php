@extends('layouts.admin')
@section('title', 'Add Employee')
@section('page-title', 'Add Employee')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Employee Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Add New Employee</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Fill in the details to register a new staff member.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li style="font-size:13.5px;">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Personal Information --}}
        <div class="form-section anim-fade-up delay-1">
            <div class="form-section-header">
                <div class="form-section-icon"
                    style="background:linear-gradient(135deg,var(--primary-start),var(--primary-end));">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Personal Information</div>
                    <div style="font-size:12px;color:var(--text-secondary);">Basic details and identification</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Extension (Jr./Sr.)</label>
                    <input type="text" name="ex_name" class="form-control" value="{{ old('ex_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Place of Birth</label>
                    <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_num" class="form-control" value="{{ old('contact_num') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Blood Pressure No.</label>
                    <input type="text" name="bp_no" class="form-control" value="{{ old('bp_no') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Disability</label>
                    <input type="text" name="disability" class="form-control" value="{{ old('disability') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        {{-- Government IDs --}}
        <div class="form-section anim-fade-up delay-2">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:linear-gradient(135deg,#A78BFA,#8B5CF6);">
                    <i class="bi bi-card-list"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Government IDs</div>
                    <div style="font-size:12px;color:var(--text-secondary);">SSS, PhilHealth, Pag-IBIG and TIN</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Employee No.</label>
                    <input type="text" name="employee_no" class="form-control" value="{{ old('employee_no') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">PhilHealth</label>
                    <input type="text" name="philhealth" class="form-control" value="{{ old('philhealth') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pag-IBIG</label>
                    <input type="text" name="pagibig" class="form-control" value="{{ old('pagibig') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">TIN</label>
                    <input type="text" name="TIN" class="form-control" value="{{ old('TIN') }}">
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="form-section anim-fade-up delay-3">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:linear-gradient(135deg,#34D399,#059669);">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Address</div>
                    <div style="font-size:12px;color:var(--text-secondary);">Current residential address</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Street</label>
                    <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Barangay</label>
                    <input type="text" name="street_brgy" class="form-control" value="{{ old('street_brgy') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Municipality</label>
                    <input type="text" name="municipality" class="form-control" value="{{ old('municipality') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Province</label>
                    <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Region</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region') }}">
                </div>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="form-section anim-fade-up delay-4">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:linear-gradient(135deg,#FCD34D,#F59E0B);">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Account Information</div>
                    <div style="font-size:12px;color:var(--text-secondary);">Login credentials and role assignment</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Government Email <span class="text-danger">*</span></label>
                    <input type="email" name="gov_email" class="form-control" value="{{ old('gov_email') }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Position / Role <span class="text-danger">*</span></label>
                    <select name="user_pos" class="form-select" required>
                        <option value="">Select Position</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_desc }}"
                                {{ old('user_pos') == $role->role_desc ? 'selected' : '' }}>
                                {{ $role->role_desc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Initial Password</label>
                    <input type="text" class="form-control" value="Auto-generated" disabled
                        style="background:var(--bg);color:var(--text-secondary);">
                    <div class="form-text">Password will be auto-generated and emailed.</div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 anim-fade-up delay-5 mt-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-2"></i> Save Employee
            </button>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>

    </form>

    <style>
        .form-section {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .form-section-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }
    </style>

@endsection

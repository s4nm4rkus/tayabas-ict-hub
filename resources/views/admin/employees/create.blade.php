@extends('layouts.admin-layout.admin')

@section('title', 'Add Employee')
@section('page-title', 'Add Employee')

@section('content')

    <div class="stat-card">
        <h6 class="mb-4" style="font-size:16px; font-weight:600;">Employee Information</h6>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Personal Information --}}
            <div class="mb-4">
                <div class="nav-section ps-0 mb-3"
                    style="font-size:11px;font-weight:600;
                            color:#8892a4;text-transform:uppercase;
                            letter-spacing:0.08em;">
                    Personal Information
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                            required>
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
                        <input type="text" name="place_of_birth" class="form-control"
                            value="{{ old('place_of_birth') }}">
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
            <div class="mb-4">
                <div class="nav-section ps-0 mb-3"
                    style="font-size:11px;font-weight:600;
                            color:#8892a4;text-transform:uppercase;
                            letter-spacing:0.08em;">
                    Government IDs
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
            <div class="mb-4">
                <div class="nav-section ps-0 mb-3"
                    style="font-size:11px;font-weight:600;
                            color:#8892a4;text-transform:uppercase;
                            letter-spacing:0.08em;">
                    Address
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
                        <input type="text" name="municipality" class="form-control"
                            value="{{ old('municipality') }}">
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
            <div class="mb-4">
                <div class="nav-section ps-0 mb-3"
                    style="font-size:11px;font-weight:600;
                            color:#8892a4;text-transform:uppercase;
                            letter-spacing:0.08em;">
                    Account Information
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
                        <label class="form-label">Initial Password <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="Auto-generated" disabled>
                        <div class="form-text">Password will be auto-generated.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Employee
                </button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>

@endsection

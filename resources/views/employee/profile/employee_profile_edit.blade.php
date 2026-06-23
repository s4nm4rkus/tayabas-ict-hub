@extends('layouts.employee')
@section('title', 'Edit My Profile')
@section('page-title', 'Edit My Profile')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Account</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Edit My Profile</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Update your personal contact and address information.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9">

            <div class="alert alert-info anim-fade-up mb-4" style="font-size:13px;">
                <i class="bi bi-info-circle me-1"></i>
                Some fields like your name, birthdate, email, and government IDs are managed by HR and cannot be changed
                here.
            </div>

            <form method="POST" action="{{ route('employee.profile.update') }}">
                @csrf
                @method('PUT')

                {{-- Personal Info --}}
                <div class="stat-card anim-fade-up delay-1 mb-3">
                    <div
                        style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                        <i class="bi bi-person me-2" style="color:#4A90E2;"></i>Personal Information
                    </div>

                    {{-- Read-only fields --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="{{ $employee->last_name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="{{ $employee->first_name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ $employee->gender }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Birthdate</label>
                            <input type="text" class="form-control" value="{{ $employee->birthdate?->format('F d, Y') }}"
                                disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gov Email</label>
                            <input type="text" class="form-control" value="{{ $employee->gov_email }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Employee No.</label>
                            <input type="text" class="form-control" value="{{ $employee->employee_no ?? '—' }}" disabled>
                        </div>
                    </div>

                    <div
                        style="font-size:12px;color:var(--text-secondary);margin-bottom:1rem;padding:8px 12px;
                        background:rgba(107,114,128,0.06);border-radius:8px;border:1px solid var(--border);">
                        <i class="bi bi-lock me-1"></i> Fields above are managed by HR. Fields below are editable.
                    </div>

                    {{-- Editable fields --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                value="{{ old('middle_name', $employee->middle_name) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Extension (Jr., Sr., etc.)</label>
                            <input type="text" name="ex_name" class="form-control"
                                value="{{ old('ex_name', $employee->ex_name) }}" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control"
                                value="{{ old('place_of_birth', $employee->place_of_birth) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_num" class="form-control"
                                value="{{ old('contact_num', $employee->contact_num) }}" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">BP No.</label>
                            <input type="text" name="bp_no" class="form-control"
                                value="{{ old('bp_no', $employee->bp_no) }}" maxlength="50">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Disability (if any)</label>
                            <input type="text" name="disability" class="form-control"
                                value="{{ old('disability', $employee->disability) }}" maxlength="100">
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="stat-card anim-fade-up delay-2 mb-3">
                    <div
                        style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                        <i class="bi bi-geo-alt me-2" style="color:#22C55E;"></i>Address
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Street</label>
                            <input type="text" name="street" class="form-control"
                                value="{{ old('street', $employee->street) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Barangay</label>
                            <input type="text" name="street_brgy" class="form-control"
                                value="{{ old('street_brgy', $employee->street_brgy) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Municipality</label>
                            <input type="text" name="municipality" class="form-control"
                                value="{{ old('municipality', $employee->municipality) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Province</label>
                            <input type="text" name="province" class="form-control"
                                value="{{ old('province', $employee->province) }}" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Region</label>
                            <input type="text" name="region" class="form-control"
                                value="{{ old('region', $employee->region) }}" maxlength="100">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 anim-fade-up delay-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-2"></i> Save Changes
                    </button>
                    <a href="{{ route('employee.profile.show') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>

        </div>
    </div>

@endsection

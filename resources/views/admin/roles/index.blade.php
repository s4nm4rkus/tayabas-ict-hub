@extends('layouts.admin')
@section('title', 'Roles')
@section('page-title', 'Roles')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Roles Management</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Define positions and their categories.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Add Role Form --}}
        <div class="col-md-4 anim-fade-up delay-1">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-plus-circle"></i> Add New Role
                </div>
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Position Name <span class="text-danger">*</span></label>
                        <input type="text" name="role_desc" class="form-control" value="{{ old('role_desc') }}"
                            placeholder="e.g. Teacher I" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="role_cat" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="Teaching" {{ old('role_cat') == 'Teaching' ? 'selected' : '' }}>Teaching</option>
                            <option value="Non-Teaching" {{ old('role_cat') == 'Non-Teaching' ? 'selected' : '' }}>Non-Teaching
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Type</label>
                        <select name="role_type" class="form-select">
                            <option value="">Select Type</option>
                            <option value="Employee" {{ old('role_type') == 'Employee' ? 'selected' : '' }}>Employee
                            </option>
                            <option value="Department Head" {{ old('role_type') == 'Department Head' ? 'selected' : '' }}>
                                Department Head</option>
                            <option value="HR" {{ old('role_type') == 'HR' ? 'selected' : '' }}>HR</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus me-1"></i> Add Role
                    </button>
                </form>
            </div>
        </div>

        {{-- Roles List --}}
        <div class="col-md-8 anim-fade-up delay-2">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-person-badge"></i> All Roles
                    <span
                        style="margin-left:8px;font-size:12px;font-weight:400;
                             color:var(--text-secondary);background:var(--bg);
                             padding:2px 10px;border-radius:99px;border:1px solid var(--border);">
                        {{ $roles->count() }} roles
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td style="font-weight:600;font-size:13.5px;">{{ $role->role_desc }}</td>
                                    <td>
                                        <span
                                            class="status-badge {{ $role->role_cat === 'Teaching' ? 'badge-info' : 'badge-warning' }}">
                                            {{ $role->role_cat ?? '—' }}
                                        </span>
                                    </td>
                                    <td style="font-size:13px;color:var(--text-secondary);">{{ $role->role_type ?? '—' }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role->role_id) }}"
                                            onsubmit="return confirm('Delete this role?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm"
                                                style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                               border:1px solid rgba(239,68,68,0.15);border-radius:8px;padding:4px 9px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                        No roles yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

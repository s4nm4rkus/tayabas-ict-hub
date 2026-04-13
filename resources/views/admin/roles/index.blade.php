@extends('layouts.admin')

@section('title', 'Roles Management')
@section('page-title', 'Roles Management')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Add Role Form --}}
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-plus-circle"></i> Add New Role
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="role_desc" class="form-control" value="{{ old('role_desc') }}"
                            placeholder="e.g. Teacher I" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="role_cat" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Teaching" {{ old('role_cat') == 'Teaching' ? 'selected' : '' }}>Teaching
                            </option>
                            <option value="Non-Teaching" {{ old('role_cat') == 'Non-Teaching' ? 'selected' : '' }}>
                                Non-Teaching</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="role_type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Employee" {{ old('role_type') == 'Employee' ? 'selected' : '' }}>Employee
                            </option>
                            <option value="Department Head" {{ old('role_type') == 'Department Head' ? 'selected' : '' }}>
                                Department Head</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Head</label>
                        <input type="text" name="role_head" class="form-control" value="{{ old('role_head') }}"
                            placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus me-1"></i> Add Role
                    </button>
                </form>
            </div>
        </div>

        {{-- Roles List --}}
        <div class="col-md-8">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-person-badge"></i> All Roles
                    <span style="font-size:12px;font-weight:400;color:#8892a4;margin-left:8px;">
                        {{ $roles->count() }} total
                    </span>
                </div>

                {{-- Teaching --}}
                <div class="mb-3">
                    <div
                        style="font-size:11px;font-weight:600;color:#4f8ef7;
                            text-transform:uppercase;letter-spacing:0.08em;
                            margin-bottom:8px;">
                        Teaching
                    </div>
                    @foreach ($roles->where('role_cat', 'Teaching') as $role)
                        <div class="role-row">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <span class="role-name">{{ $role->role_desc }}</span>
                                <span class="role-badge {{ $role->role_type === 'Department Head' ? 'head' : 'emp' }}">
                                    {{ $role->role_type }}
                                </span>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="openEdit({{ $role->role_id }}, '{{ $role->role_desc }}', '{{ $role->role_cat }}', '{{ $role->role_type }}', '{{ $role->role_head }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role->role_id) }}"
                                    onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    @if ($roles->where('role_cat', 'Teaching')->isEmpty())
                        <p class="text-muted" style="font-size:13px;">No teaching roles yet.</p>
                    @endif
                </div>

                {{-- Non-Teaching --}}
                <div>
                    <div
                        style="font-size:11px;font-weight:600;color:#f0ad4e;
                            text-transform:uppercase;letter-spacing:0.08em;
                            margin-bottom:8px;">
                        Non-Teaching
                    </div>
                    @foreach ($roles->where('role_cat', 'Non-Teaching') as $role)
                        <div class="role-row">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <span class="role-name">{{ $role->role_desc }}</span>
                                <span class="role-badge {{ $role->role_type === 'Department Head' ? 'head' : 'emp' }}">
                                    {{ $role->role_type }}
                                </span>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="openEdit({{ $role->role_id }}, '{{ $role->role_desc }}', '{{ $role->role_cat }}', '{{ $role->role_type }}', '{{ $role->role_head }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role->role_id) }}"
                                    onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    @if ($roles->where('role_cat', 'Non-Teaching')->isEmpty())
                        <p class="text-muted" style="font-size:13px;">No non-teaching roles yet.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:15px;">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="role_desc" id="edit_role_desc" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="role_cat" id="edit_role_cat" class="form-select" required>
                                <option value="Teaching">Teaching</option>
                                <option value="Non-Teaching">Non-Teaching</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="role_type" id="edit_role_type" class="form-select" required>
                                <option value="Employee">Employee</option>
                                <option value="Department Head">Department Head</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Head</label>
                            <input type="text" name="role_head" id="edit_role_head" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
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

        .role-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
            margin-bottom: 6px;
            transition: background 0.15s;
        }

        .role-row:hover {
            background: #fafafa;
        }

        .role-name {
            font-size: 14px;
            font-weight: 500;
            color: #1a1f2e;
        }

        .role-badge {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 99px;
            font-weight: 500;
        }

        .role-badge.emp {
            background: #e8f0fe;
            color: #1a56db;
        }

        .role-badge.head {
            background: #fff3cd;
            color: #856404;
        }
    </style>

    @push('scripts')
        <script>
            function openEdit(id, desc, cat, type, head) {
                document.getElementById('edit_role_desc').value = desc;
                document.getElementById('edit_role_cat').value = cat;
                document.getElementById('edit_role_type').value = type;
                document.getElementById('edit_role_head').value = head || '';
                document.getElementById('editRoleForm').action =
                    '/admin/roles/' + id;
                new bootstrap.Modal(document.getElementById('editRoleModal')).show();
            }
        </script>
    @endpush

@endsection

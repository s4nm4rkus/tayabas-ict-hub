@extends('layouts.admin-layout.admin')

@section('title', 'Employees')
@section('page-title', 'Employee List')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('import_passwords'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <strong>
                    <i class="bi bi-key me-1"></i>
                    Generated Passwords — Save or print this now. It will not show again.
                </strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0" style="font-size:13px;background:#fff;">
                    <thead style="background:#1a1f2e;color:#fff;">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Default Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('import_passwords') as $pw)
                            <tr>
                                <td>{{ $pw['id'] }}</td>
                                <td>{{ $pw['name'] }}</td>
                                <td>{{ $pw['email'] }}</td>
                                <td>
                                    <code style="font-size:13px;">{{ $pw['password'] }}</code>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Password format: First name + Birthdate (MMDDYYYY).
                    Employees must change password on first login.
                </small>
            </div>
        </div>
    @endif

    <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h6 class="mb-0" style="font-size:16px; font-weight:600;">
                All Employees
                <span id="rowCount" style="font-size:13px;font-weight:400;color:#8892a4;margin-left:8px;"></span>
            </h6>
            <div class="d-flex align-items-center gap-2 flex-wrap">

                {{-- Export Dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.employees.export.csv') }}">
                                <i class="bi bi-filetype-csv me-2" style="color:#28a745;"></i>
                                Download as CSV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.employees.export.pdf') }}">
                                <i class="bi bi-filetype-pdf me-2" style="color:#dc3545;"></i>
                                Download as PDF
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('admin.employees.import.form') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-upload me-1"></i> Import CSV/Excel
                </a>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus me-1"></i> Add Employee
                </a>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="d-flex gap-2 mb-3 flex-wrap">
            <input type="text" id="searchInput" class="form-control" style="max-width:280px;"
                placeholder="Search name, email, position...">
            <select id="statusFilter" class="form-select" style="max-width:150px;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="disabled">Disabled</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="employeeTable">
                <thead>
                    <tr style="font-size:13px; color:#8892a4; user-select:none;">
                        <th class="sortable" data-col="0" style="cursor:pointer; white-space:nowrap;">
                            Employee ID <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="1" style="cursor:pointer; white-space:nowrap;">
                            Name <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="2" style="cursor:pointer; white-space:nowrap;">
                            Position <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="3" style="cursor:pointer; white-space:nowrap;">
                            Email <span class="sort-icon">↕</span>
                        </th>
                        <th class="sortable" data-col="4" style="cursor:pointer; white-space:nowrap;">
                            Status <span class="sort-icon">↕</span>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody style="font-size:14px;">
                    @forelse($employees as $emp)
                        <tr>
                            <td>{{ $emp->user?->user_id ?? '—' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($emp->photo_path)
                                        <img src="{{ asset('storage/' . $emp->photo_path) }}" width="32" height="32"
                                            class="rounded-circle object-fit-cover">
                                    @else
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;
                                                    background:#e8f0fe;color:#4f8ef7;
                                                    display:flex;align-items:center;
                                                    justify-content:center;font-size:13px;
                                                    font-weight:600;flex-shrink:0;">
                                            {{ strtoupper(substr($emp->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div style="font-weight:500;">{{ $emp->full_name }}</div>
                                </div>
                            </td>
                            <td>{{ $emp->employment->position ?? '—' }}</td>
                            <td>{{ $emp->gov_email }}</td>
                            <td>
                                @if ($emp->user?->user_stat === 'Enabled')
                                    <span class="badge" style="background:#d4edda;color:#155724;font-size:12px;">
                                        Active
                                    </span>
                                @else
                                    <span class="badge" style="background:#f8d7da;color:#721c24;font-size:12px;">
                                        Disabled
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.employees.show', $emp->user_id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $emp->user_id) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="noResults" style="display:none;text-align:center;padding:2rem;color:#8892a4;font-size:14px;">
            No employees match your search.
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const table = document.getElementById('employeeTable');
        const tbody = table.querySelector('tbody');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const rowCount = document.getElementById('rowCount');
        const noResults = document.getElementById('noResults');

        let sortCol = -1;
        let sortAsc = true;

        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function() {
                const col = parseInt(this.dataset.col);
                if (sortCol === col) {
                    sortAsc = !sortAsc;
                } else {
                    sortCol = col;
                    sortAsc = true;
                }

                document.querySelectorAll('.sortable .sort-icon').forEach(icon => {
                    icon.textContent = '↕';
                    icon.style.color = '#c0c0c0';
                });
                this.querySelector('.sort-icon').textContent = sortAsc ? '↑' : '↓';
                this.querySelector('.sort-icon').style.color = '#4f8ef7';

                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.sort((a, b) => {
                    const aText = a.cells[col]?.innerText.trim().toLowerCase() ?? '';
                    const bText = b.cells[col]?.innerText.trim().toLowerCase() ?? '';
                    return sortAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });
                rows.forEach(row => tbody.appendChild(row));
                updateCount();
            });
        });

        function filterRows() {
            const search = searchInput.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            const rows = Array.from(tbody.querySelectorAll('tr'));
            let visible = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const statusBadge = row.cells[4]?.innerText.trim().toLowerCase() ?? '';
                const matchSearch = text.includes(search);
                const matchStatus = status === '' || statusBadge.includes(status);

                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visible === 0 ? 'block' : 'none';
            rowCount.textContent = `(${visible} shown)`;
        }

        function updateCount() {
            const visible = Array.from(tbody.querySelectorAll('tr'))
                .filter(r => r.style.display !== 'none').length;
            rowCount.textContent = `(${visible} shown)`;
        }

        searchInput.addEventListener('keyup', filterRows);
        statusFilter.addEventListener('change', filterRows);
        updateCount();
    </script>
@endpush

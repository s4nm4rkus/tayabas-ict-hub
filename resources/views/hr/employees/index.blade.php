@extends('layouts.hr')
@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Employee Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">All Employees</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">View and export employee records.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size:15px;font-weight:700;color:var(--text-primary);">Employee List</span>
                <span id="rowCount"
                    style="font-size:12px;font-weight:500;color:var(--text-secondary);
                background:var(--bg);padding:2px 10px;border-radius:99px;
                border:1px solid var(--border);"></span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end"
                        style="border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow-md);">
                        <li>
                            <a class="dropdown-item" href="{{ route('hr.employees.export.csv') }}"
                                style="font-size:13.5px;padding:8px 16px;">
                                <i class="bi bi-filetype-csv me-2" style="color:#22C55E;"></i> CSV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('hr.employees.export.pdf') }}"
                                style="font-size:13.5px;padding:8px 16px;">
                                <i class="bi bi-filetype-pdf me-2" style="color:#EF4444;"></i> PDF
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <div style="position:relative;flex:1;min-width:200px;max-width:320px;">
                <i class="bi bi-search"
                    style="position:absolute;left:12px;top:50%;
               transform:translateY(-50%);color:var(--text-secondary);font-size:14px;pointer-events:none;"></i>
                <input type="text" id="searchInput" class="form-control" style="padding-left:36px;"
                    placeholder="Search name, email, position...">
            </div>
            <select id="statusFilter" class="form-select" style="max-width:150px;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="disabled">Disabled</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="employeeTable">
                <thead>
                    <tr>
                        <th class="sortable" data-col="0" style="cursor:pointer;">
                            Employee ID <span class="sort-icon"
                                style="color:var(--text-secondary);margin-left:4px;">↕</span>
                        </th>
                        <th class="sortable" data-col="1" style="cursor:pointer;">
                            Name <span class="sort-icon" style="color:var(--text-secondary);margin-left:4px;">↕</span>
                        </th>
                        <th class="sortable" data-col="2" style="cursor:pointer;">
                            Position <span class="sort-icon" style="color:var(--text-secondary);margin-left:4px;">↕</span>
                        </th>
                        <th class="sortable" data-col="3" style="cursor:pointer;">
                            Email <span class="sort-icon" style="color:var(--text-secondary);margin-left:4px;">↕</span>
                        </th>
                        <th class="sortable" data-col="4" style="cursor:pointer;">
                            Status <span class="sort-icon" style="color:var(--text-secondary);margin-left:4px;">↕</span>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr>
                            <td>
                                <span
                                    style="font-size:12px;font-weight:600;padding:2px 8px;border-radius:6px;
                                     background:rgba(52,211,153,0.1);color:#059669;">
                                    {{ $emp->user?->user_id ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($emp->photo_path)
                                        <img src="{{ asset('storage/' . $emp->photo_path) }}"
                                            style="width:34px;height:34px;border-radius:50%;
                                            object-fit:cover;border:2px solid var(--border);">
                                    @else
                                        <div
                                            style="width:34px;height:34px;border-radius:50%;
                                            background:linear-gradient(135deg,#34D399,#059669);
                                            color:#fff;display:flex;align-items:center;
                                            justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($emp->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text-primary);">
                                        {{ $emp->full_name }}
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:13.5px;color:var(--text-secondary);">
                                {{ $emp->employment?->position ?? '—' }}
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $emp->gov_email }}</td>
                            <td>
                                @if ($emp->user?->user_stat === 'Enabled')
                                    <span class="status-badge badge-success">
                                        <i class="bi bi-circle-fill" style="font-size:7px;"></i> Active
                                    </span>
                                @else
                                    <span class="status-badge badge-danger">
                                        <i class="bi bi-circle-fill" style="font-size:7px;"></i> Disabled
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('hr.employees.show', $emp->user_id) }}" class="btn btn-sm"
                                    style="background:rgba(52,211,153,0.1);color:#059669;
                                  border:1px solid rgba(52,211,153,0.2);border-radius:8px;
                                  padding:5px 10px;"
                                    title="View Profile">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-people"
                                    style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="noResults" style="display:none;text-align:center;padding:3rem;color:var(--text-secondary);">
            <i class="bi bi-search" style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
            No employees match your search.
        </div>
    </div>

    @push('scripts')
        <script>
            const table = document.getElementById('employeeTable');
            const tbody = table.querySelector('tbody');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const rowCount = document.getElementById('rowCount');
            const noResults = document.getElementById('noResults');
            let sortCol = -1,
                sortAsc = true;

            document.querySelectorAll('.sortable').forEach(th => {
                th.addEventListener('click', function() {
                    const col = parseInt(this.dataset.col);
                    sortAsc = sortCol === col ? !sortAsc : true;
                    sortCol = col;
                    document.querySelectorAll('.sort-icon').forEach(i => {
                        i.textContent = '↕';
                        i.style.color = 'var(--text-secondary)';
                    });
                    const icon = this.querySelector('.sort-icon');
                    icon.textContent = sortAsc ? '↑' : '↓';
                    icon.style.color = '#059669';
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    rows.sort((a, b) => {
                        const aT = a.cells[col]?.innerText.trim().toLowerCase() ?? '';
                        const bT = b.cells[col]?.innerText.trim().toLowerCase() ?? '';
                        return sortAsc ? aT.localeCompare(bT) : bT.localeCompare(aT);
                    });
                    rows.forEach(r => tbody.appendChild(r));
                    updateCount();
                });
            });

            function filterRows() {
                const s = searchInput.value.toLowerCase();
                const st = statusFilter.value.toLowerCase();
                let visible = 0;
                Array.from(tbody.querySelectorAll('tr')).forEach(row => {
                    const text = row.innerText.toLowerCase();
                    const statusText = row.cells[4]?.innerText.trim().toLowerCase() ?? '';
                    const ok = text.includes(s) && (st === '' || statusText.includes(st));
                    row.style.display = ok ? '' : 'none';
                    if (ok) visible++;
                });
                noResults.style.display = visible === 0 ? 'block' : 'none';
                rowCount.textContent = visible + ' employees';
            }

            function updateCount() {
                const v = Array.from(tbody.querySelectorAll('tr'))
                    .filter(r => r.style.display !== 'none').length;
                rowCount.textContent = v + ' employees';
            }

            searchInput.addEventListener('keyup', filterRows);
            statusFilter.addEventListener('change', filterRows);
            updateCount();
        </script>
    @endpush

@endsection

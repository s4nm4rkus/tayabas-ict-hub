@extends('layouts.hr')
@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Leave Requests</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Review and process employee leave applications.</p>
        </div>
    </div>

    {{-- ── Pending ── --}}
    <div class="stat-card anim-fade-up delay-1 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Pending Approval</div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">Awaiting your review</div>
            </div>
            <span
                style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:99px;
                         background:rgba(245,158,11,0.12);color:#B45309;border:1px solid rgba(245,158,11,0.2);">
                {{ $leaves->count() }} pending
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Leave Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Filed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</td>
                            <td style="font-size:13px;color:var(--text-secondary);">{{ $leave->position }}</td>
                            <td>
                                <span class="status-badge badge-info">
                                    {{ Str::limit($leave->leave_types ?? $leave->leavetype, 25) }}
                                </span>
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>
                            <td style="font-size:12px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('hr.leave.show', $leave->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-calendar-check"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No pending leave requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Recently Processed — with Search + Sort + Stats ── --}}
    <div class="stat-card anim-fade-up delay-2">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Recently Processed</div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">
                    Last 20 processed requests
                </div>
            </div>
            {{-- Quick stats --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span id="stat-all"
                    style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:99px;cursor:pointer;
                             background:rgba(110,168,254,0.12);color:#1D4ED8;border:1px solid rgba(110,168,254,0.2);">
                    All <span id="count-all">0</span>
                </span>
                <span id="stat-approved"
                    style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:99px;cursor:pointer;
                             background:rgba(34,197,94,0.10);color:#15803D;border:1px solid rgba(34,197,94,0.2);">
                    Approved <span id="count-approved">0</span>
                </span>
                <span id="stat-declined"
                    style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:99px;cursor:pointer;
                             background:rgba(239,68,68,0.10);color:#B91C1C;border:1px solid rgba(239,68,68,0.2);">
                    Declined <span id="count-declined">0</span>
                </span>
                <span id="stat-pending"
                    style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:99px;cursor:pointer;
                             background:rgba(245,158,11,0.10);color:#B45309;border:1px solid rgba(245,158,11,0.2);">
                    In Progress <span id="count-pending">0</span>
                </span>
            </div>
        </div>

        {{-- Search + Sort Controls --}}
        <div class="d-flex gap-2 mb-3 flex-wrap">
            {{-- Search --}}
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="bi bi-search"
                    style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                          color:var(--text-secondary);font-size:13px;pointer-events:none;"></i>
                <input type="text" id="searchInput" placeholder="Search employee, leave type..." class="form-control"
                    style="padding-left:32px;font-size:13px;">
            </div>

            {{-- Sort By --}}
            <select id="sortBy" class="form-select" style="width:auto;font-size:13px;min-width:150px;">
                <option value="date-desc">Latest First</option>
                <option value="date-asc">Oldest First</option>
                <option value="name-asc">Name A→Z</option>
                <option value="name-desc">Name Z→A</option>
                <option value="days-desc">Most Days</option>
                <option value="days-asc">Fewest Days</option>
            </select>

            {{-- Filter Status --}}
            <select id="filterStatus" class="form-select" style="width:auto;font-size:13px;min-width:140px;">
                <option value="all">All Status</option>
                <option value="approved">Approved</option>
                <option value="declined">Declined</option>
                <option value="pending">In Progress</option>
            </select>

            {{-- Clear --}}
            <button id="clearFilters" class="btn btn-outline-secondary btn-sm" style="font-size:12px;white-space:nowrap;">
                <i class="bi bi-x me-1"></i> Clear
            </button>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="processedTable">
                <thead>
                    <tr>
                        <th class="sortable-th" data-col="0" style="cursor:pointer;user-select:none;">
                            Employee <i class="bi bi-chevron-expand sort-icon" style="font-size:10px;opacity:0.5;"></i>
                        </th>
                        <th>Leave Type</th>
                        <th class="sortable-th" data-col="2" style="cursor:pointer;user-select:none;">
                            Start <i class="bi bi-chevron-expand sort-icon" style="font-size:10px;opacity:0.5;"></i>
                        </th>
                        <th class="sortable-th" data-col="3" style="cursor:pointer;user-select:none;">
                            End <i class="bi bi-chevron-expand sort-icon" style="font-size:10px;opacity:0.5;"></i>
                        </th>
                        <th class="sortable-th" data-col="4" style="cursor:pointer;user-select:none;">
                            Days <i class="bi bi-chevron-expand sort-icon" style="font-size:10px;opacity:0.5;"></i>
                        </th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="processedBody">
                    @forelse($processed as $leave)
                        @php
                            $statusLower = strtolower($leave->leave_status);
                            $statusGroup = match (true) {
                                str_contains($statusLower, 'approved') => 'approved',
                                str_contains($statusLower, 'declined') => 'declined',
                                default => 'pending',
                            };
                            $cls = match ($statusGroup) {
                                'approved' => 'badge-success',
                                'declined' => 'badge-danger',
                                default => 'badge-warning',
                            };
                        @endphp
                        <tr data-name="{{ strtolower($leave->fullname) }}"
                            data-type="{{ strtolower($leave->leave_types ?? ($leave->leavetype ?? '')) }}"
                            data-status="{{ $statusGroup }}" data-days="{{ $leave->total_days }}"
                            data-start="{{ $leave->start_date?->format('Y-m-d') }}"
                            data-updated="{{ $leave->updated_at?->format('Y-m-d H:i:s') }}">
                            <td>
                                <div style="font-weight:600;font-size:13.5px;">{{ $leave->fullname }}</div>
                                <div style="font-size:11px;color:var(--text-secondary);">{{ $leave->position }}</div>
                            </td>
                            <td style="font-size:13px;">
                                {{ Str::limit($leave->leave_types ?? $leave->leavetype, 25) }}
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge badge-gray">{{ $leave->total_days }}d</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $cls }}">{{ $leave->leave_status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('hr.leave.show', $leave->id) }}"
                                    style="font-size:12px;color:#1D4ED8;font-weight:600;text-decoration:none;
                                          display:inline-flex;align-items:center;gap:4px;padding:4px 8px;
                                          background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.2);
                                          border-radius:6px;">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                No processed leaves yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- No results message --}}
        <div id="noResults" style="display:none;text-align:center;padding:2rem;color:var(--text-secondary);">
            <i class="bi bi-search" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.3;"></i>
            <div style="font-size:13px;">No results found. <a href="#" id="clearFromMsg"
                    style="color:#1D4ED8;font-weight:600;text-decoration:none;">Clear filters</a></div>
        </div>

        {{-- Row count --}}
        <div id="rowCount" style="font-size:12px;color:var(--text-secondary);margin-top:10px;text-align:right;">
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const sortBy = document.getElementById('sortBy');
                const filterStatus = document.getElementById('filterStatus');
                const clearBtn = document.getElementById('clearFilters');
                const clearMsg = document.getElementById('clearFromMsg');
                const tbody = document.getElementById('processedBody');
                const noResults = document.getElementById('noResults');
                const rowCount = document.getElementById('rowCount');

                // ── Counts ────────────────────────────────────────────────────────────
                const allRows = Array.from(tbody.querySelectorAll('tr[data-name]'));

                function updateCounts() {
                    let counts = {
                        approved: 0,
                        declined: 0,
                        pending: 0
                    };
                    allRows.forEach(r => {
                        counts[r.dataset.status]++;
                    });
                    document.getElementById('count-all').textContent = allRows.length;
                    document.getElementById('count-approved').textContent = counts.approved;
                    document.getElementById('count-declined').textContent = counts.declined;
                    document.getElementById('count-pending').textContent = counts.pending;
                }
                updateCounts();

                // ── Quick filter badges ────────────────────────────────────────────────
                ['all', 'approved', 'declined', 'pending'].forEach(key => {
                    document.getElementById('stat-' + key)?.addEventListener('click', () => {
                        filterStatus.value = key === 'all' ? 'all' : key;
                        applyFilters();
                    });
                });

                // ── Apply filters + search + sort ─────────────────────────────────────
                function applyFilters() {
                    const query = searchInput.value.toLowerCase().trim();
                    const status = filterStatus.value;
                    const sort = sortBy.value;

                    let visible = allRows.filter(row => {
                        const matchSearch = !query ||
                            row.dataset.name.includes(query) ||
                            row.dataset.type.includes(query);
                        const matchStatus = status === 'all' || row.dataset.status === status;
                        return matchSearch && matchStatus;
                    });

                    // Sort
                    visible.sort((a, b) => {
                        switch (sort) {
                            case 'date-desc':
                                return (b.dataset.updated || '').localeCompare(a.dataset.updated || '');
                            case 'date-asc':
                                return (a.dataset.updated || '').localeCompare(b.dataset.updated || '');
                            case 'name-asc':
                                return a.dataset.name.localeCompare(b.dataset.name);
                            case 'name-desc':
                                return b.dataset.name.localeCompare(a.dataset.name);
                            case 'days-desc':
                                return parseInt(b.dataset.days || 0) - parseInt(a.dataset.days || 0);
                            case 'days-asc':
                                return parseInt(a.dataset.days || 0) - parseInt(b.dataset.days || 0);
                            default:
                                return 0;
                        }
                    });

                    // Hide all, show visible in sorted order
                    allRows.forEach(r => r.style.display = 'none');
                    visible.forEach(r => {
                        r.style.display = '';
                        tbody.appendChild(r); // reorder in DOM
                    });

                    // No results
                    noResults.style.display = visible.length === 0 ? 'block' : 'none';
                    rowCount.textContent = visible.length > 0 ?
                        `Showing ${visible.length} of ${allRows.length} records` :
                        '';
                }

                // ── Clear ──────────────────────────────────────────────────────────────
                function clearAll() {
                    searchInput.value = '';
                    sortBy.value = 'date-desc';
                    filterStatus.value = 'all';
                    applyFilters();
                }
                clearBtn.addEventListener('click', clearAll);
                clearMsg?.addEventListener('click', (e) => {
                    e.preventDefault();
                    clearAll();
                });

                // ── Live search ────────────────────────────────────────────────────────
                searchInput.addEventListener('input', applyFilters);
                sortBy.addEventListener('change', applyFilters);
                filterStatus.addEventListener('change', applyFilters);

                // ── Column header sort ─────────────────────────────────────────────────
                document.querySelectorAll('.sortable-th').forEach(th => {
                    th.addEventListener('click', () => {
                        const col = parseInt(th.dataset.col);
                        const map = {
                            0: ['name-asc', 'name-desc'],
                            2: ['date-asc', 'date-desc'],
                            3: ['date-asc', 'date-desc'],
                            4: ['days-asc', 'days-desc']
                        };
                        if (!map[col]) return;
                        sortBy.value = sortBy.value === map[col][0] ? map[col][1] : map[col][0];
                        applyFilters();
                    });
                });

                // Initial render
                applyFilters();
            });
        </script>
    @endpush

@endsection

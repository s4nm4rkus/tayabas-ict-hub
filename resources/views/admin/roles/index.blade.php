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

    {{-- ── Stats Row ── --}}
    <div class="row g-3 mb-4 anim-fade-up">
        @php
            $teaching = $roles->where('role_cat', 'Teaching')->count();
            $nonTeaching = $roles->where('role_cat', 'Non-Teaching')->count();
            $heads = $roles->where('role_type', 'Department Head')->count();
        @endphp
        <div class="col-6 col-md-4">
            <div class="stat-card h-100" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Total Roles</div>
                <div style="font-size:28px;font-weight:700;color:var(--text-primary);line-height:1;">{{ $roles->count() }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card h-100" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Teaching</div>
                <div style="font-size:28px;font-weight:700;color:#4A90E2;line-height:1;">{{ $teaching }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card h-100" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Non-Teaching</div>
                <div style="font-size:28px;font-weight:700;color:#F59E0B;line-height:1;">{{ $nonTeaching }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ── Add Role Form ── --}}
        <div class="col-12 col-md-4 anim-fade-up delay-1">
            <div class="stat-card h-100">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(110,168,254,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-plus-lg" style="color:#4A90E2;font-size:13px;"></i>
                    </div>
                    Add New Role
                </div>

                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Position Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="role_desc" class="form-control" value="{{ old('role_desc') }}"
                            placeholder="e.g. Teacher I" required style="font-size:13px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Category <span class="text-danger">*</span>
                        </label>
                        {{-- Custom radio cards for category --}}
                        <div style="display:flex;gap:8px;">
                            @foreach (['Teaching', 'Non-Teaching'] as $cat)
                                <label style="flex:1;cursor:pointer;">
                                    <input type="radio" name="role_cat" value="{{ $cat }}"
                                        {{ old('role_cat') == $cat ? 'checked' : '' }} required style="display:none;"
                                        class="cat-radio">
                                    <div class="cat-card"
                                        style="padding:10px 8px;border:1.5px solid var(--border);
                                        border-radius:var(--radius-sm);text-align:center;font-size:12px;
                                        font-weight:600;color:var(--text-secondary);transition:all 0.2s;
                                        background:var(--bg);">
                                        <i class="bi {{ $cat === 'Teaching' ? 'bi-mortarboard' : 'bi-briefcase' }}"
                                            style="display:block;font-size:16px;margin-bottom:4px;"></i>
                                        {{ $cat }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Type
                        </label>
                        <select name="role_type" class="form-select" style="font-size:13px;">
                            <option value="">— None —</option>
                            <option value="Employee" {{ old('role_type') == 'Employee' ? 'selected' : '' }}>Employee
                            </option>
                            <option value="Department Head"{{ old('role_type') == 'Department Head' ? 'selected' : '' }}>
                                Department Head</option>
                            <option value="HR" {{ old('role_type') == 'HR' ? 'selected' : '' }}>HR
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus me-1"></i> Add Role
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Roles List ── --}}
        <div class="col-12 col-md-8 anim-fade-up delay-2">
            <div class="stat-card">
                {{-- Header with search --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;
                    gap:12px;margin-bottom:1rem;padding-bottom:0.75rem;
                    border-bottom:1px solid var(--border);flex-wrap:wrap;">
                    <div
                        style="font-size:14px;font-weight:700;color:var(--text-primary);
                        display:flex;align-items:center;gap:8px;">
                        <div
                            style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,0.12);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-badge" style="color:#8B5CF6;font-size:13px;"></i>
                        </div>
                        All Roles
                        <span
                            style="font-size:11px;font-weight:500;color:var(--text-secondary);
                             background:var(--bg);padding:2px 8px;border-radius:99px;
                             border:1px solid var(--border);">
                            {{ $roles->count() }}
                        </span>
                    </div>
                    {{-- Search --}}
                    <div style="position:relative;min-width:180px;flex:1;max-width:260px;">
                        <i class="bi bi-search"
                            style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                            color:var(--text-secondary);font-size:12px;pointer-events:none;"></i>
                        <input type="text" id="roleSearch" placeholder="Search roles..."
                            style="width:100%;padding:6px 10px 6px 30px;border:1px solid var(--border);
                               border-radius:var(--radius-sm);font-size:12px;background:var(--bg);
                               color:var(--text-primary);outline:none;">
                    </div>
                </div>

                {{-- Filter tabs --}}
                <div style="display:flex;gap:6px;margin-bottom:1rem;flex-wrap:wrap;" id="filterTabs">
                    @foreach (['All', 'Teaching', 'Non-Teaching'] as $tab)
                        <button onclick="filterRoles('{{ $tab }}')"
                            class="filter-tab {{ $tab === 'All' ? 'active' : '' }}"
                            style="padding:4px 14px;border-radius:99px;font-size:12px;font-weight:600;
                               border:1.5px solid var(--border);background:var(--bg);
                               color:var(--text-secondary);cursor:pointer;transition:all 0.2s;">
                            {{ $tab }}
                        </button>
                    @endforeach
                </div>

                {{-- Roles table --}}
                <div class="table-responsive" style="max-height:520px;overflow-y:auto;">
                    <table class="table mb-0" id="rolesTable">
                        <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                            <tr>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;
                                    border-bottom:2px solid var(--border);">
                                    Position</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;
                                    border-bottom:2px solid var(--border);">
                                    Category</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;
                                    border-bottom:2px solid var(--border);">
                                    Type</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;
                                    border-bottom:2px solid var(--border);width:60px;">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr class="role-row" data-cat="{{ $role->role_cat }}"
                                    data-name="{{ strtolower($role->role_desc) }}" style="transition:background 0.15s;">
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                            {{ $role->role_desc }}
                                        </div>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        @if ($role->role_cat)
                                            <span
                                                class="status-badge {{ $role->role_cat === 'Teaching' ? 'badge-info' : 'badge-warning' }}">
                                                {{ $role->role_cat }}
                                            </span>
                                        @else
                                            <span style="font-size:12px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        @if ($role->role_type)
                                            <span
                                                style="font-size:12px;font-weight:500;
                                                color:{{ $role->role_type === 'Department Head' ? '#8B5CF6' : ($role->role_type === 'HR' ? '#22C55E' : 'var(--text-secondary)') }};">
                                                {{ $role->role_type }}
                                            </span>
                                        @else
                                            <span style="font-size:12px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role->role_id) }}"
                                            onsubmit="return confirm('Delete {{ $role->role_desc }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="width:30px;height:30px;border-radius:8px;
                                                   background:rgba(239,68,68,0.08);color:#B91C1C;
                                                   border:1px solid rgba(239,68,68,0.15);
                                                   display:flex;align-items:center;justify-content:center;
                                                   cursor:pointer;transition:all 0.2s;padding:0;"
                                                onmouseover="this.style.background='rgba(239,68,68,0.18)'"
                                                onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                                <i class="bi bi-trash" style="font-size:12px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                        <i class="bi bi-inbox"
                                            style="font-size:32px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                                        No roles yet. Add one to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Empty search state --}}
                <div id="noResults" style="display:none;text-align:center;padding:2rem;color:var(--text-secondary);">
                    <i class="bi bi-search" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    <div style="font-size:13px;">No roles match your search.</div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // ── Category radio card highlight ─────────────────────────────────────
            document.querySelectorAll('.cat-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.cat-card').forEach(card => {
                        card.style.borderColor = 'var(--border)';
                        card.style.color = 'var(--text-secondary)';
                        card.style.background = 'var(--bg)';
                    });
                    if (this.checked) {
                        const card = this.nextElementSibling;
                        card.style.borderColor = '#4A90E2';
                        card.style.color = '#4A90E2';
                        card.style.background = 'rgba(110,168,254,0.08)';
                    }
                });

                // Init on load if old value is checked
                if (radio.checked) {
                    const card = radio.nextElementSibling;
                    card.style.borderColor = '#4A90E2';
                    card.style.color = '#4A90E2';
                    card.style.background = 'rgba(110,168,254,0.08)';
                }
            });

            // ── Filter tabs ───────────────────────────────────────────────────────
            function filterRoles(cat) {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(btn => {
                    btn.classList.remove('active');
                    btn.style.background = 'var(--bg)';
                    btn.style.color = 'var(--text-secondary)';
                    btn.style.borderColor = 'var(--border)';
                });
                event.target.classList.add('active');
                event.target.style.background = 'rgba(110,168,254,0.12)';
                event.target.style.color = '#4A90E2';
                event.target.style.borderColor = 'rgba(110,168,254,0.3)';

                // Filter rows
                document.querySelectorAll('.role-row').forEach(row => {
                    const rowCat = row.dataset.cat;
                    row.style.display = (cat === 'All' || rowCat === cat) ? '' : 'none';
                });

                checkNoResults();
            }

            // ── Search ────────────────────────────────────────────────────────────
            document.getElementById('roleSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.role-row').forEach(row => {
                    const name = row.dataset.name;
                    row.style.display = name.includes(q) ? '' : 'none';
                });
                checkNoResults();
            });

            function checkNoResults() {
                const visible = [...document.querySelectorAll('.role-row')]
                    .filter(r => r.style.display !== 'none').length;
                document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
            }
        </script>
    @endpush

@endsection

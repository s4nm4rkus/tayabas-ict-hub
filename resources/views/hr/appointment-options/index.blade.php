@extends('layouts.hr')
@section('title', 'Appointment Options')
@section('page-title', 'Appointment Options')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Page hero --}}
    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Appointment Options</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Manage dropdown choices for Nature and Status of Appointment.
            </p>
        </div>
    </div>

    {{-- Stats row --}}
    <div class="row g-3 mb-4 anim-fade-up">
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Total Options
                </div>
                <div style="font-size:28px;font-weight:700;color:var(--text-primary);line-height:1;">
                    {{ $natureOptions->count() + $statusOptions->count() }}
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Nature of Appoint.
                </div>
                <div style="font-size:28px;font-weight:700;color:#4A90E2;line-height:1;">
                    {{ $natureOptions->count() }}
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Status of Appoint.
                </div>
                <div style="font-size:28px;font-weight:700;color:#F59E0B;line-height:1;">
                    {{ $statusOptions->count() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ── Add / Edit Form ── --}}
        <div class="col-12 col-md-4 anim-fade-up delay-1">
            <div class="stat-card">

                {{-- Form header --}}
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);
                     margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                     display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(52,211,153,0.12);
                         display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-plus-lg" style="color:#059669;font-size:13px;" id="formIcon"></i>
                    </div>
                    <span id="formTitle">Add New Option</span>
                </div>

                {{-- Add form --}}
                <form method="POST" action="{{ route('hr.appointment-options.store') }}" id="addForm">
                    @csrf

                    {{-- Type selector --}}
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Option Type <span class="text-danger">*</span>
                        </label>
                        <div style="display:flex;gap:8px;">
                            @foreach ([['value' => 'nature_appoint', 'label' => 'Nature', 'icon' => 'bi-file-earmark-text'], ['value' => 'status_appoint', 'label' => 'Status', 'icon' => 'bi-bookmark-check']] as $type)
                                <label style="flex:1;cursor:pointer;">
                                    <input type="radio" name="option_type" value="{{ $type['value'] }}"
                                        {{ old('option_type', 'nature_appoint') === $type['value'] ? 'checked' : '' }}
                                        required style="display:none;" class="type-radio">
                                    <div class="type-card"
                                        style="padding:10px 8px;border:1.5px solid var(--border);
                                        border-radius:var(--radius-sm);text-align:center;font-size:12px;
                                        font-weight:600;color:var(--text-secondary);transition:all 0.2s;
                                        background:var(--bg);">
                                        <i class="bi {{ $type['icon'] }}"
                                            style="display:block;font-size:16px;margin-bottom:4px;"></i>
                                        {{ $type['label'] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Value (key) <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="option_value" class="form-control" value="{{ old('option_value') }}"
                            placeholder="e.g. PERMANENT" required style="font-size:13px;text-transform:uppercase;"
                            oninput="this.value = this.value.toUpperCase().replace(/\s+/g,'_')">
                        <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                            Uppercase, underscores only. Used internally.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Display Label <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="option_label" class="form-control" value="{{ old('option_label') }}"
                            placeholder="e.g. Permanent" required style="font-size:13px;">
                        <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                            What employees see in the dropdown.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}"
                            min="0" style="font-size:13px;">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus me-1"></i> Add Option
                    </button>
                </form>

                {{-- Edit form (hidden by default) --}}
                <form method="POST" id="editForm" style="display:none;">
                    @csrf @method('PUT')

                    {{-- Read-only type indicator --}}
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Type
                        </label>
                        <div id="editTypeBadge"
                            style="padding:8px 12px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;
                            background:rgba(74,144,226,0.1);color:#2563EB;border:1px solid rgba(74,144,226,0.2);display:inline-block;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Value (key)
                        </label>
                        <input type="text" id="editOptionValue" class="form-control"
                            style="font-size:13px;background:var(--bg);opacity:0.6;" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Display Label <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="option_label" id="editOptionLabel" class="form-control" required
                            style="font-size:13px;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0"
                            style="font-size:13px;">
                    </div>

                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-primary" style="flex:1;">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <button type="button" onclick="cancelEdit()" class="btn btn-outline-secondary">
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- ── Options Lists ── --}}
        <div class="col-12 col-md-8 anim-fade-up delay-2">

            {{-- Active filter / search bar --}}
            <div class="stat-card mb-3" style="padding:0.85rem 1.25rem;">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    {{-- Filter tabs --}}
                    <div style="display:flex;gap:6px;flex:1;">
                        @foreach (['All' => 'All', 'nature_appoint' => 'Nature', 'status_appoint' => 'Status'] as $val => $lbl)
                            <button onclick="filterOptions('{{ $val }}', this)" class="filter-tab"
                                style="padding:4px 14px;border-radius:99px;font-size:12px;font-weight:600;
                                border:1.5px solid {{ $val === 'All' ? 'rgba(52,211,153,0.4)' : 'var(--border)' }};
                                background:{{ $val === 'All' ? 'rgba(52,211,153,0.1)' : 'var(--bg)' }};
                                color:{{ $val === 'All' ? '#059669' : 'var(--text-secondary)' }};
                                cursor:pointer;transition:all 0.2s;">
                                {{ $lbl }}
                            </button>
                        @endforeach
                    </div>
                    {{-- Search --}}
                    <div style="position:relative;min-width:180px;">
                        <i class="bi bi-search"
                            style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-secondary);font-size:12px;pointer-events:none;"></i>
                        <input type="text" id="optionSearch" placeholder="Search options…"
                            style="width:100%;padding:6px 10px 6px 30px;border:1px solid var(--border);
                            border-radius:var(--radius-sm);font-size:12px;background:var(--bg);
                            color:var(--text-primary);outline:none;transition:border-color 0.15s;"
                            onfocus="this.style.borderColor='#34D399'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
            </div>

            {{-- Nature of Appointment table --}}
            <div class="stat-card mb-3 options-section" data-type="nature_appoint">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);
                     margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                     display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(74,144,226,0.12);
                         display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-file-earmark-text" style="color:#4A90E2;font-size:13px;"></i>
                    </div>
                    Nature of Appointment
                    <span
                        style="font-size:11px;font-weight:500;color:var(--text-secondary);background:var(--bg);
                          padding:2px 8px;border-radius:99px;border:1px solid var(--border);">
                        {{ $natureOptions->count() }}
                    </span>
                </div>

                <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                    <table class="table mb-0">
                        <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                            <tr>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);width:40px;">
                                    #</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Label</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Value Key</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Status</th>
                                <th style="border-bottom:2px solid var(--border);width:80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($natureOptions as $opt)
                                <tr class="option-row" data-type="nature_appoint"
                                    data-label="{{ strtolower($opt->option_label) }}">
                                    <td
                                        style="padding:10px 12px;vertical-align:middle;font-size:12px;color:var(--text-secondary);">
                                        {{ $opt->sort_order }}</td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                            {{ $opt->option_label }}</div>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <code
                                            style="font-size:11px;background:var(--bg);padding:2px 7px;border-radius:5px;color:#8B5CF6;border:1px solid var(--border);">{{ $opt->option_value }}</code>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        @if ($opt->is_active)
                                            <span class="status-badge badge-success" style="font-size:10px;">Active</span>
                                        @else
                                            <span class="status-badge badge-secondary"
                                                style="font-size:10px;">Inactive</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="display:flex;gap:5px;align-items:center;">
                                            {{-- Toggle active --}}
                                            <form method="POST"
                                                action="{{ route('hr.appointment-options.toggle', $opt->id) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    title="{{ $opt->is_active ? 'Deactivate' : 'Activate' }}"
                                                    style="width:28px;height:28px;border-radius:7px;
                                                    background:{{ $opt->is_active ? 'rgba(34,197,94,0.1)' : 'rgba(156,163,175,0.1)' }};
                                                    color:{{ $opt->is_active ? '#15803D' : '#6B7280' }};
                                                    border:1px solid {{ $opt->is_active ? 'rgba(34,197,94,0.2)' : 'rgba(156,163,175,0.2)' }};
                                                    display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;">
                                                    <i class="bi {{ $opt->is_active ? 'bi-eye' : 'bi-eye-slash' }}"
                                                        style="font-size:11px;"></i>
                                                </button>
                                            </form>
                                            {{-- Edit --}}
                                            <button
                                                onclick="openEdit({{ $opt->id }}, '{{ addslashes($opt->option_label) }}', '{{ $opt->option_value }}', '{{ $opt->option_type }}', {{ $opt->sort_order }})"
                                                style="width:28px;height:28px;border-radius:7px;background:rgba(110,168,254,0.1);color:#2563EB;border:1px solid rgba(110,168,254,0.2);display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;"
                                                onmouseover="this.style.background='rgba(110,168,254,0.2)'"
                                                onmouseout="this.style.background='rgba(110,168,254,0.1)'">
                                                <i class="bi bi-pencil" style="font-size:11px;"></i>
                                            </button>
                                            {{-- Delete --}}
                                            <form method="POST"
                                                action="{{ route('hr.appointment-options.destroy', $opt->id) }}"
                                                onsubmit="return confirm('Delete \'{{ addslashes($opt->option_label) }}\'?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    style="width:28px;height:28px;border-radius:7px;background:rgba(239,68,68,0.08);color:#B91C1C;border:1px solid rgba(239,68,68,0.15);display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;"
                                                    onmouseover="this.style.background='rgba(239,68,68,0.18)'"
                                                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                                    <i class="bi bi-trash" style="font-size:11px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                        <i class="bi bi-inbox"
                                            style="font-size:28px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                                        No nature options yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Status of Appointment table --}}
            <div class="stat-card options-section" data-type="status_appoint">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);
                     margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                     display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(245,158,11,0.12);
                         display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-bookmark-check" style="color:#F59E0B;font-size:13px;"></i>
                    </div>
                    Status of Appointment
                    <span
                        style="font-size:11px;font-weight:500;color:var(--text-secondary);background:var(--bg);
                          padding:2px 8px;border-radius:99px;border:1px solid var(--border);">
                        {{ $statusOptions->count() }}
                    </span>
                </div>

                <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                    <table class="table mb-0">
                        <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                            <tr>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);width:40px;">
                                    #</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Label</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Value Key</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Status</th>
                                <th style="border-bottom:2px solid var(--border);width:80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusOptions as $opt)
                                <tr class="option-row" data-type="status_appoint"
                                    data-label="{{ strtolower($opt->option_label) }}">
                                    <td
                                        style="padding:10px 12px;vertical-align:middle;font-size:12px;color:var(--text-secondary);">
                                        {{ $opt->sort_order }}</td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                            {{ $opt->option_label }}</div>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <code
                                            style="font-size:11px;background:var(--bg);padding:2px 7px;border-radius:5px;color:#8B5CF6;border:1px solid var(--border);">{{ $opt->option_value }}</code>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        @if ($opt->is_active)
                                            <span class="status-badge badge-success" style="font-size:10px;">Active</span>
                                        @else
                                            <span class="status-badge badge-secondary"
                                                style="font-size:10px;">Inactive</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="display:flex;gap:5px;align-items:center;">
                                            <form method="POST"
                                                action="{{ route('hr.appointment-options.toggle', $opt->id) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    title="{{ $opt->is_active ? 'Deactivate' : 'Activate' }}"
                                                    style="width:28px;height:28px;border-radius:7px;
                                                    background:{{ $opt->is_active ? 'rgba(34,197,94,0.1)' : 'rgba(156,163,175,0.1)' }};
                                                    color:{{ $opt->is_active ? '#15803D' : '#6B7280' }};
                                                    border:1px solid {{ $opt->is_active ? 'rgba(34,197,94,0.2)' : 'rgba(156,163,175,0.2)' }};
                                                    display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;">
                                                    <i class="bi {{ $opt->is_active ? 'bi-eye' : 'bi-eye-slash' }}"
                                                        style="font-size:11px;"></i>
                                                </button>
                                            </form>
                                            <button
                                                onclick="openEdit({{ $opt->id }}, '{{ addslashes($opt->option_label) }}', '{{ $opt->option_value }}', '{{ $opt->option_type }}', {{ $opt->sort_order }})"
                                                style="width:28px;height:28px;border-radius:7px;background:rgba(110,168,254,0.1);color:#2563EB;border:1px solid rgba(110,168,254,0.2);display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;"
                                                onmouseover="this.style.background='rgba(110,168,254,0.2)'"
                                                onmouseout="this.style.background='rgba(110,168,254,0.1)'">
                                                <i class="bi bi-pencil" style="font-size:11px;"></i>
                                            </button>
                                            <form method="POST"
                                                action="{{ route('hr.appointment-options.destroy', $opt->id) }}"
                                                onsubmit="return confirm('Delete \'{{ addslashes($opt->option_label) }}\'?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    style="width:28px;height:28px;border-radius:7px;background:rgba(239,68,68,0.08);color:#B91C1C;border:1px solid rgba(239,68,68,0.15);display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:all 0.2s;"
                                                    onmouseover="this.style.background='rgba(239,68,68,0.18)'"
                                                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                                    <i class="bi bi-trash" style="font-size:11px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                        <i class="bi bi-inbox"
                                            style="font-size:28px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                                        No status options yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="noResults" style="display:none;text-align:center;padding:2rem;color:var(--text-secondary);">
                    <i class="bi bi-search" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    <div style="font-size:13px;">No options match your search.</div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // ── Type radio card highlight ──────────────────────────────────────
            function initTypeRadios() {
                document.querySelectorAll('.type-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        document.querySelectorAll('.type-card').forEach(c => {
                            c.style.borderColor = 'var(--border)';
                            c.style.color = 'var(--text-secondary)';
                            c.style.background = 'var(--bg)';
                        });
                        if (this.checked) {
                            const card = this.nextElementSibling;
                            card.style.borderColor = '#34D399';
                            card.style.color = '#059669';
                            card.style.background = 'rgba(52,211,153,0.08)';
                        }
                    });
                    if (radio.checked) {
                        radio.nextElementSibling.style.borderColor = '#34D399';
                        radio.nextElementSibling.style.color = '#059669';
                        radio.nextElementSibling.style.background = 'rgba(52,211,153,0.08)';
                    }
                });
            }
            initTypeRadios();

            // ── Filter tabs ───────────────────────────────────────────────────
            function filterOptions(type, btn) {
                document.querySelectorAll('.filter-tab').forEach(b => {
                    b.style.background = 'var(--bg)';
                    b.style.color = 'var(--text-secondary)';
                    b.style.borderColor = 'var(--border)';
                });
                btn.style.background = 'rgba(52,211,153,0.1)';
                btn.style.color = '#059669';
                btn.style.borderColor = 'rgba(52,211,153,0.4)';

                document.querySelectorAll('.options-section').forEach(section => {
                    if (type === 'All' || section.dataset.type === type) {
                        section.style.display = '';
                    } else {
                        section.style.display = 'none';
                    }
                });
            }

            // ── Search ────────────────────────────────────────────────────────
            document.getElementById('optionSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.option-row').forEach(row => {
                    row.style.display = row.dataset.label.includes(q) ? '' : 'none';
                });
            });

            // ── Edit form ─────────────────────────────────────────────────────
            function openEdit(id, label, value, type, sort) {
                document.getElementById('addForm').style.display = 'none';
                document.getElementById('editForm').style.display = 'block';

                document.getElementById('formIcon').className = 'bi bi-pencil';
                document.getElementById('formIcon').style.color = '#4A90E2';
                document.getElementById('formTitle').textContent = 'Edit Option';

                document.getElementById('editForm').action =
                    '{{ url('hr/appointment-options') }}/' + id;

                document.getElementById('editOptionLabel').value = label;
                document.getElementById('editOptionValue').value = value;
                document.getElementById('editSortOrder').value = sort;

                const typeLabels = {
                    'nature_appoint': '📄 Nature of Appointment',
                    'status_appoint': '🔖 Status of Appointment',
                };
                document.getElementById('editTypeBadge').textContent = typeLabels[type] || type;

                document.getElementById('editForm').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            function cancelEdit() {
                document.getElementById('editForm').style.display = 'none';
                document.getElementById('addForm').style.display = 'block';

                document.getElementById('formIcon').className = 'bi bi-plus-lg';
                document.getElementById('formIcon').style.color = '#059669';
                document.getElementById('formTitle').textContent = 'Add New Option';
            }
        </script>
    @endpush

@endsection

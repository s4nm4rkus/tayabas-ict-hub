@extends('layouts.hr')
@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('import_errors'))
        <div class="alert alert-warning alert-dismissible fade show anim-fade-up">
            <strong>Some rows were skipped:</strong>
            <ul class="mb-0 mt-2">
                @foreach (session('import_errors') as $err)
                    <li style="font-size:13px;">{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Attendance</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Record, import and export employee attendance data.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- ════════════════════════════════════
         LEFT COLUMN
    ════════════════════════════════════ --}}
        <div class="col-12 col-md-4 anim-fade-up delay-1">

            {{-- ── Tab Navigation ── --}}
            <div style="display:flex;gap:6px;margin-bottom:12px;">
                <button onclick="switchAttTab('record')" id="atttab-record"
                    style="flex:1;padding:8px 6px;border-radius:var(--radius-sm);font-size:12px;
                       font-weight:600;border:1.5px solid rgba(110,168,254,0.3);cursor:pointer;
                       background:rgba(110,168,254,0.1);color:#4A90E2;transition:all 0.2s;">
                    <i class="bi bi-plus-circle me-1"></i> Record
                </button>
                <button onclick="switchAttTab('tools')" id="atttab-tools"
                    style="flex:1;padding:8px 6px;border-radius:var(--radius-sm);font-size:12px;
                       font-weight:600;border:1.5px solid var(--border);cursor:pointer;
                       background:var(--bg);color:var(--text-secondary);transition:all 0.2s;">
                    <i class="bi bi-arrow-left-right me-1"></i> Tools
                </button>
            </div>

            {{-- ── Record Attendance Panel ── --}}
            <div id="attpanel-record" class="stat-card mb-3">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(110,168,254,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock" style="color:#4A90E2;font-size:13px;"></i>
                    </div>
                    Record Attendance
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('hr.attendance.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);
                            text-transform:uppercase;letter-spacing:0.05em;">
                            Employee <span class="text-danger">*</span>
                        </label>
                        <select name="user_id" class="form-select" required style="font-size:13px;">
                            <option value="">Select employee</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);
                            text-transform:uppercase;letter-spacing:0.05em;">
                            Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="t_date" class="form-control" value="{{ old('t_date', date('Y-m-d')) }}"
                            required style="font-size:13px;">
                    </div>

                    {{-- AM Session --}}
                    <div
                        style="background:rgba(110,168,254,0.05);border-radius:var(--radius-sm);
                        padding:12px;margin-bottom:10px;border:1px solid rgba(110,168,254,0.12);">
                        <div
                            style="font-size:11px;font-weight:700;color:#4A90E2;
                            text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;
                            display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-sun"></i> AM Session
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label" style="font-size:11px;color:var(--text-secondary);">Time
                                    In</label>
                                <input type="time" name="am_time_in" class="form-control form-control-sm"
                                    value="{{ old('am_time_in') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:11px;color:var(--text-secondary);">Time
                                    Out</label>
                                <input type="time" name="am_time_out" class="form-control form-control-sm"
                                    value="{{ old('am_time_out') }}">
                            </div>
                        </div>
                    </div>

                    {{-- PM Session --}}
                    <div
                        style="background:rgba(245,158,11,0.05);border-radius:var(--radius-sm);
                        padding:12px;margin-bottom:16px;border:1px solid rgba(245,158,11,0.12);">
                        <div
                            style="font-size:11px;font-weight:700;color:#F59E0B;
                            text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;
                            display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-moon"></i> PM Session
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label" style="font-size:11px;color:var(--text-secondary);">Time
                                    In</label>
                                <input type="time" name="pm_time_in" class="form-control form-control-sm"
                                    value="{{ old('pm_time_in') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size:11px;color:var(--text-secondary);">Time
                                    Out</label>
                                <input type="time" name="pm_time_out" class="form-control form-control-sm"
                                    value="{{ old('pm_time_out') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Save Attendance
                    </button>
                </form>
            </div>

            {{-- ── Import / Export Tools Panel ── --}}
            <div id="attpanel-tools" class="stat-card mb-3" style="display:none;">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(52,211,153,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-arrow-left-right" style="color:#22C55E;font-size:13px;"></i>
                    </div>
                    Import / Export
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('hr.attendance.import.form') }}"
                        style="display:flex;align-items:center;gap:10px;padding:12px;
                           border-radius:var(--radius-sm);text-decoration:none;
                           background:rgba(52,211,153,0.07);border:1px solid rgba(52,211,153,0.15);
                           transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(52,211,153,0.14)'"
                        onmouseout="this.style.background='rgba(52,211,153,0.07)'">
                        <div
                            style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                            background:linear-gradient(135deg,#34D399,#059669);
                            display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-upload" style="font-size:14px;color:white;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Import CSV/Excel</div>
                            <div style="font-size:11px;color:var(--text-secondary);">Bulk upload attendance</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:12px;color:var(--text-secondary);"></i>
                    </a>

                    <a href="{{ route('hr.attendance.export.csv') }}"
                        style="display:flex;align-items:center;gap:10px;padding:12px;
                           border-radius:var(--radius-sm);text-decoration:none;
                           background:rgba(110,168,254,0.07);border:1px solid rgba(110,168,254,0.15);
                           transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(110,168,254,0.14)'"
                        onmouseout="this.style.background='rgba(110,168,254,0.07)'">
                        <div
                            style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                            background:linear-gradient(135deg,#6EA8FE,#4A90E2);
                            display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-download" style="font-size:14px;color:white;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Export CSV</div>
                            <div style="font-size:11px;color:var(--text-secondary);">Download attendance data</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:12px;color:var(--text-secondary);"></i>
                    </a>

                    <a href="{{ route('hr.attendance.template') }}"
                        style="display:flex;align-items:center;gap:10px;padding:12px;
                           border-radius:var(--radius-sm);text-decoration:none;
                           background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.15);
                           transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(245,158,11,0.14)'"
                        onmouseout="this.style.background='rgba(245,158,11,0.07)'">
                        <div
                            style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                            background:linear-gradient(135deg,#FCD34D,#F59E0B);
                            display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-file-earmark-arrow-down" style="font-size:14px;color:white;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Download Template</div>
                            <div style="font-size:11px;color:var(--text-secondary);">CSV import template</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:12px;color:var(--text-secondary);"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════
         RIGHT COLUMN — Records
    ════════════════════════════════════ --}}
        <div class="col-12 col-md-8 anim-fade-up delay-2">
            <div class="stat-card">

                {{-- Header --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;
                    gap:12px;margin-bottom:1rem;padding-bottom:0.75rem;
                    border-bottom:1px solid var(--border);flex-wrap:wrap;">
                    <div
                        style="font-size:13px;font-weight:700;color:var(--text-primary);
                        display:flex;align-items:center;gap:8px;">
                        <div
                            style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,0.12);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock-history" style="color:#8B5CF6;font-size:13px;"></i>
                        </div>
                        Attendance Records
                    </div>
                    <span
                        style="font-size:11px;font-weight:500;color:var(--text-secondary);
                         background:var(--bg);padding:2px 10px;border-radius:99px;
                         border:1px solid var(--border);">
                        {{ $attendance->total() }} records
                    </span>
                </div>

                {{-- Filter bar --}}
                <form method="GET" action="{{ route('hr.attendance.index') }}"
                    style="display:flex;gap:8px;margin-bottom:1rem;flex-wrap:wrap;align-items:center;">
                    <input type="date" name="date" class="form-control form-control-sm"
                        style="max-width:160px;font-size:12px;" value="{{ request('date') }}">
                    <select name="employee_id" class="form-select form-select-sm"
                        style="max-width:200px;font-size:12px;">
                        <option value="">All Employees</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm" style="font-size:12px;">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    @if (request('date') || request('employee_id'))
                        <a href="{{ route('hr.attendance.index') }}" class="btn btn-outline-secondary btn-sm"
                            style="font-size:12px;">
                            <i class="bi bi-x me-1"></i> Clear
                        </a>
                    @endif
                </form>

                {{-- Table --}}
                <div class="table-responsive" style="max-height:520px;overflow-y:auto;">
                    <table class="table mb-0">
                        <thead style="position:sticky;top:0;background:var(--card-bg);z-index:1;">
                            <tr>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Employee</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Date</th>
                                <th style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);"
                                    colspan="2">AM</th>
                                <th style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);"
                                    colspan="2">PM</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Hrs</th>
                                <th
                                    style="font-size:11px;font-weight:700;text-transform:uppercase;
                                    letter-spacing:0.06em;color:var(--text-secondary);
                                    padding:8px 12px;border-bottom:2px solid var(--border);">
                                    Pts</th>
                                <th style="border-bottom:2px solid var(--border);width:44px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendance as $att)
                                <tr style="transition:background 0.15s;">
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                            {{ $att->fullname }}
                                        </div>
                                    </td>
                                    <td style="padding:10px 12px;vertical-align:middle;">
                                        <div style="font-size:12px;color:var(--text-primary);">
                                            {{ $att->t_date?->format('M d') }}
                                        </div>
                                        <div style="font-size:10px;color:var(--text-secondary);">
                                            {{ $att->t_date?->format('Y') }}
                                        </div>
                                    </td>
                                    {{-- AM In --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        @if ($att->am_time_in)
                                            <span
                                                style="font-size:11px;font-weight:600;
                                                padding:2px 6px;border-radius:4px;
                                                background:rgba(110,168,254,0.1);color:#4A90E2;">
                                                {{ $att->am_time_in }}
                                            </span>
                                        @else
                                            <span style="font-size:11px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    {{-- AM Out --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        @if ($att->am_time_out)
                                            <span
                                                style="font-size:11px;font-weight:600;
                                                padding:2px 6px;border-radius:4px;
                                                background:rgba(110,168,254,0.06);color:#4A90E2;">
                                                {{ $att->am_time_out }}
                                            </span>
                                        @else
                                            <span style="font-size:11px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    {{-- PM In --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        @if ($att->pm_time_in)
                                            <span
                                                style="font-size:11px;font-weight:600;
                                                padding:2px 6px;border-radius:4px;
                                                background:rgba(245,158,11,0.1);color:#F59E0B;">
                                                {{ $att->pm_time_in }}
                                            </span>
                                        @else
                                            <span style="font-size:11px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    {{-- PM Out --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        @if ($att->pm_time_out)
                                            <span
                                                style="font-size:11px;font-weight:600;
                                                padding:2px 6px;border-radius:4px;
                                                background:rgba(245,158,11,0.06);color:#F59E0B;">
                                                {{ $att->pm_time_out }}
                                            </span>
                                        @else
                                            <span style="font-size:11px;color:var(--text-secondary);">—</span>
                                        @endif
                                    </td>
                                    {{-- Hours --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        <span class="status-badge badge-info" style="font-size:11px;">
                                            {{ $att->total_hours }}h
                                        </span>
                                    </td>
                                    {{-- Points --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        <span style="font-size:12px;font-weight:700;color:#059669;">
                                            {{ round((0.42 / 8) * (float) $att->total_hours, 4) }}
                                        </span>
                                    </td>
                                    {{-- Delete --}}
                                    <td style="padding:10px 8px;vertical-align:middle;">
                                        <form method="POST" action="{{ route('hr.attendance.destroy', $att->id) }}"
                                            onsubmit="return confirm('Delete this record?')">
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
                                    <td colspan="9"
                                        style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                        <i class="bi bi-clock"
                                            style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                        <div style="font-size:13px;">No attendance records found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">{{ $attendance->links() }}</div>

            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function switchAttTab(tab) {
                // Hide all panels
                document.getElementById('attpanel-record').style.display = 'none';
                document.getElementById('attpanel-tools').style.display = 'none';

                // Reset tabs
                ['record', 'tools'].forEach(t => {
                    const btn = document.getElementById('atttab-' + t);
                    btn.style.background = 'var(--bg)';
                    btn.style.color = 'var(--text-secondary)';
                    btn.style.borderColor = 'var(--border)';
                });

                // Show selected
                document.getElementById('attpanel-' + tab).style.display = 'block';
                const activeBtn = document.getElementById('atttab-' + tab);
                activeBtn.style.background = 'rgba(110,168,254,0.1)';
                activeBtn.style.color = '#4A90E2';
                activeBtn.style.borderColor = 'rgba(110,168,254,0.3)';
            }
        </script>
    @endpush

@endsection

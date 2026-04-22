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

        {{-- Record Form --}}
        <div class="col-md-4 anim-fade-up delay-1">
            <div class="stat-card mb-3">
                <div class="info-card-title">
                    <i class="bi bi-plus-circle"></i> Record Attendance
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('hr.attendance.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Select employee</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="t_date" class="form-control" value="{{ old('t_date', date('Y-m-d')) }}"
                            required>
                    </div>
                    <div style="background:var(--bg);border-radius:var(--radius-sm);padding:12px;margin-bottom:12px;">
                        <div
                            style="font-size:11px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">
                            AM Session
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Time In</label>
                                <input type="time" name="am_time_in" class="form-control"
                                    value="{{ old('am_time_in') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Time Out</label>
                                <input type="time" name="am_time_out" class="form-control"
                                    value="{{ old('am_time_out') }}">
                            </div>
                        </div>
                    </div>
                    <div style="background:var(--bg);border-radius:var(--radius-sm);padding:12px;margin-bottom:16px;">
                        <div
                            style="font-size:11px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">
                            PM Session
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Time In</label>
                                <input type="time" name="pm_time_in" class="form-control"
                                    value="{{ old('pm_time_in') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Time Out</label>
                                <input type="time" name="pm_time_out" class="form-control"
                                    value="{{ old('pm_time_out') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Save Attendance
                    </button>
                </form>
            </div>

            {{-- Import / Export Tools --}}
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-arrow-left-right"></i> Import / Export
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('hr.attendance.import.form') }}"
                        style="display:flex;align-items:center;gap:10px;padding:10px 12px;
                          border-radius:10px;text-decoration:none;
                          background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.15);
                          transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(52,211,153,0.14)'"
                        onmouseout="this.style.background='rgba(52,211,153,0.08)'">
                        <div
                            style="width:30px;height:30px;border-radius:8px;flex-shrink:0;
                                background:linear-gradient(135deg,#34D399,#059669);
                                display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-upload" style="font-size:13px;color:white;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Import CSV/Excel</div>
                            <div style="font-size:11px;color:var(--text-secondary);">Bulk upload attendance</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:12px;color:var(--text-secondary);"></i>
                    </a>

                    <a href="{{ route('hr.attendance.export.csv') }}"
                        style="display:flex;align-items:center;gap:10px;padding:10px 12px;
                          border-radius:10px;text-decoration:none;
                          background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.15);
                          transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(110,168,254,0.14)'"
                        onmouseout="this.style.background='rgba(110,168,254,0.08)'">
                        <div
                            style="width:30px;height:30px;border-radius:8px;flex-shrink:0;
                                background:linear-gradient(135deg,#6EA8FE,#4A90E2);
                                display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-download" style="font-size:13px;color:white;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Export CSV</div>
                            <div style="font-size:11px;color:var(--text-secondary);">Download attendance data</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:12px;color:var(--text-secondary);"></i>
                    </a>

                    <a href="{{ route('hr.attendance.template') }}"
                        style="display:flex;align-items:center;gap:10px;padding:10px 12px;
                          border-radius:10px;text-decoration:none;
                          background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.15);
                          transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(245,158,11,0.14)'"
                        onmouseout="this.style.background='rgba(245,158,11,0.08)'">
                        <div
                            style="width:30px;height:30px;border-radius:8px;flex-shrink:0;
                                background:linear-gradient(135deg,#FCD34D,#F59E0B);
                                display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-file-earmark-arrow-down" style="font-size:13px;color:white;"></i>
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

        {{-- Attendance List --}}
        <div class="col-md-8 anim-fade-up delay-2">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-clock-history"></i> Attendance Records
                </div>

                <form method="GET" action="{{ route('hr.attendance.index') }}" class="d-flex gap-2 mb-3 flex-wrap">
                    <input type="date" name="date" class="form-control" style="max-width:180px;"
                        value="{{ request('date') }}">
                    <select name="employee_id" class="form-select" style="max-width:200px;">
                        <option value="">All Employees</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('hr.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>AM In</th>
                                <th>AM Out</th>
                                <th>PM In</th>
                                <th>PM Out</th>
                                <th>Hours</th>
                                <th>Points</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendance as $att)
                                <tr>
                                    <td style="font-weight:500;font-size:13px;">{{ $att->fullname }}</td>
                                    <td style="font-size:13px;">{{ $att->t_date?->format('M d, Y') }}</td>
                                    <td style="font-size:12px;color:var(--text-secondary);">{{ $att->am_time_in ?? '—' }}
                                    </td>
                                    <td style="font-size:12px;color:var(--text-secondary);">{{ $att->am_time_out ?? '—' }}
                                    </td>
                                    <td style="font-size:12px;color:var(--text-secondary);">{{ $att->pm_time_in ?? '—' }}
                                    </td>
                                    <td style="font-size:12px;color:var(--text-secondary);">{{ $att->pm_time_out ?? '—' }}
                                    </td>
                                    <td><span class="status-badge badge-info">{{ $att->total_hours }}h</span></td>
                                    <td style="font-size:12px;color:#059669;font-weight:600;">
                                        {{ round((0.42 / 8) * (float) $att->total_hours, 4) }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('hr.attendance.destroy', $att->id) }}"
                                            onsubmit="return confirm('Delete this record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                               border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                               padding:4px 9px;cursor:pointer;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
                                        style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                        <i class="bi bi-clock"
                                            style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                        No attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $attendance->links() }}</div>
            </div>
        </div>

    </div>

@endsection

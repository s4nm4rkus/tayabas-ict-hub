@extends('layouts.hr')
@section('title', 'Import Attendance')
@section('page-title', 'Import Attendance')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Attendance</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Import Attendance</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Upload a CSV or Excel file to bulk import attendance records.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Instructions --}}
            <div class="form-section anim-fade-up delay-1 mb-4">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:linear-gradient(135deg,#34D399,#059669);">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Column Requirements</div>
                        <div style="font-size:12px;color:var(--text-secondary);">Your file must have these headers in the
                            first row</div>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Column Header</th>
                                <th>Required?</th>
                                <th>Example</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13.5px;">
                            @foreach ([['gov_email', 'Required*', 'juan@deped.gov.ph'], ['employee_id', 'Required*', 'ICTHUB-2026-0001'], ['date', 'Required', '2026-04-16 (YYYY-MM-DD)'], ['am_time_in', 'Optional', '07:30'], ['am_time_out', 'Optional', '12:00'], ['pm_time_in', 'Optional', '13:00'], ['pm_time_out', 'Optional', '17:00']] as [$col, $req, $ex])
                                <tr>
                                    <td><code
                                            style="background:rgba(52,211,153,0.1);padding:2px 8px;border-radius:4px;font-size:12px;">{{ $col }}</code>
                                    </td>
                                    <td>
                                        @if (str_contains($req, 'Required'))
                                            <span class="status-badge badge-danger">{{ $req }}</span>
                                        @else
                                            <span class="status-badge badge-gray">Optional</span>
                                        @endif
                                    </td>
                                    <td style="color:var(--text-secondary);">{{ $ex }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mb-3" style="font-size:13px;">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>* Either</strong> <code>gov_email</code> or <code>employee_id</code> is required to identify the
                    employee.
                    Leave points are auto-calculated from total hours on import.
                </div>

                <a href="{{ route('hr.attendance.template') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download Template
                </a>
            </div>

            {{-- Upload Form --}}
            <div class="form-section anim-fade-up delay-2">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:linear-gradient(135deg,#6EA8FE,#4A90E2);">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Upload File</div>
                        <div style="font-size:12px;color:var(--text-secondary);">Accepted: .csv, .xlsx, .xls — Max 10MB
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('hr.attendance.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i> Import Attendance
                        </button>
                        <a href="{{ route('hr.attendance.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <style>
        .form-section {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .form-section-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }
    </style>

@endsection

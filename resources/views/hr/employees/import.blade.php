@extends('layouts.hr')
@section('title', 'Import Employees')
@section('page-title', 'Import Employees')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Employee Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Import Employees</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Upload a CSV or Excel file to bulk register employees.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9">

            {{-- Instructions --}}
            <div class="form-section anim-fade-up delay-1 mb-4">
                <div class="form-section-header">
                    <div class="form-section-icon"
                        style="background:linear-gradient(135deg,var(--primary-start),var(--primary-end));">
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
                            @foreach ([['last_name', 'Required', 'dela Cruz'], ['first_name', 'Required', 'Juan'], ['gov_email', 'Required', 'juan@deped.gov.ph'], ['birthdate', 'Recommended', '1990-01-15 (YYYY-MM-DD)'], ['middle_name', 'Optional', 'Santos'], ['gender', 'Optional', 'Male / Female'], ['contact_num', 'Optional', '09171234567'], ['position', 'Optional', 'Teacher I'], ['employee_no', 'Optional', '2025-001'], ['philhealth', 'Optional', '123456789'], ['pagibig', 'Optional', '123456789'], ['tin', 'Optional', '123-456-789'], ['municipality', 'Optional', 'Tayabas'], ['province', 'Optional', 'Quezon']] as [$col, $req, $ex])
                                <tr>
                                    <td><code
                                            style="background:rgba(110,168,254,0.1);padding:2px 8px;border-radius:4px;font-size:12px;">{{ $col }}</code>
                                    </td>
                                    <td>
                                        @if ($req === 'Required')
                                            <span class="status-badge badge-danger">Required</span>
                                        @elseif($req === 'Recommended')
                                            <span class="status-badge badge-warning">Recommended</span>
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

                <div class="alert alert-warning mb-2" style="font-size:13px;">
                    <i class="bi bi-key me-1"></i>
                    <strong>Password Policy:</strong> Default = <code>FirstnameBirthdate</code> e.g.
                    <code>Juan01151990</code>.
                    Fallback = <code>FirstnameICThub@123</code>. Passwords shown once after import.
                </div>
                <div class="alert alert-info mb-3" style="font-size:13px;">
                    <i class="bi bi-person-badge me-1"></i>
                    Employee ID (e.g. <code>ICTHUB-2026-0001</code>) is auto-assigned. Employees must change password on
                    first login.
                </div>

                <a href="{{ route('hr.employees.import.template') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download Template
                </a>
            </div>

            {{-- Upload Form --}}
            <div class="form-section anim-fade-up delay-2">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:linear-gradient(135deg,#34D399,#059669);">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Upload File</div>
                        <div style="font-size:12px;color:var(--text-secondary);">Accepted: .csv, .xlsx, .xls — Max 10MB
                        </div>
                    </div>
                </div>

                @if (session('import_errors'))
                    <div class="alert alert-warning mb-3">
                        <strong>Some rows were skipped:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach (session('import_errors') as $err)
                                <li style="font-size:13px;">{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('hr.employees.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select CSV or Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i> Import Employees
                        </button>
                        <a href="{{ route('hr.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
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

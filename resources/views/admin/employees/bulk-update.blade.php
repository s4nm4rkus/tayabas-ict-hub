@extends('layouts.admin')
@section('title', 'Bulk Update Employees')
@section('page-title', 'Bulk Update Employees')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Employee Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Bulk Update Employees</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Upload a CSV or Excel file to update existing employee records.
            </p>
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
                        <div style="font-size:12px;color:var(--text-secondary);">
                            <code>gov_email</code> is required to identify the employee. Leave other cells blank to skip
                            updating that field.
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-3" style="font-size:13px;">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>How it works:</strong> The system finds the employee by <code>gov_email</code>.
                    Only columns with values will be updated — blank cells are ignored and existing data is preserved.
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
                            @foreach ([
            ['gov_email', 'Required', 'juan@deped.gov.ph'],
            ['last_name', 'Optional', 'dela Cruz'],
            ['first_name', 'Optional', 'Juan'],
            ['middle_name', 'Optional', 'Santos'],
            ['extension', 'Optional', 'Jr.'],
            ['gender', 'Optional', 'Male / Female'],
            ['birthdate', 'Optional', '01/15/1990'],
            ['place_of_birth', 'Optional', 'Tayabas, Quezon'],
            ['contact_num', 'Optional', '09171234567'],
            ['employee_no', 'Optional', '2025-001'],
            ['philhealth', 'Optional', '123456789'],
            ['pagibig', 'Optional', '123456789'],
            ['tin', 'Optional', '123-456-789'],
            ['street', 'Optional', '123 Rizal St.'],
            ['barangay', 'Optional', 'Poblacion'],
            ['municipality', 'Optional', 'Tayabas'],
            ['province', 'Optional', 'Quezon'],
            ['region', 'Optional', 'Region IV-A'],
            ['position', 'Optional', 'Teacher II'],
            ['sub_position', 'Optional', 'Master Teacher'],
            ['date_orig_appoint', 'Optional', '01/01/2015'],
            ['salary_grade', 'Optional', '12'],
            ['salary_step', 'Optional', '1'],
            ['salary_effect_date', 'Optional', '01/01/2024'],
            ['nature_appoint', 'Optional', 'Permanent'],
            ['status_appoint', 'Optional', 'Original'],
            ['station_code', 'Optional', 'SDO-001'],
            ['plantilla_item_no', 'Optional', 'ITEM-001'],
            ['school_office_assign', 'Optional', 'Tayabas West Central School'],
            ['school_detailed_office_assign', 'Optional', 'SDO Tayabas'],
            ['vice', 'Optional', 'Juan dela Cruz'],
            ['vice_reason', 'Optional', 'Retirement'],
            ['designated_from', 'Optional', '01/01/2024'],
            ['designated_to', 'Optional', '12/31/2024'],
            ['separation', 'Optional', 'Resigned'],
            ['separation_date', 'Optional', '12/31/2024'],
        ] as [$col, $req, $ex])
                                <tr>
                                    <td>
                                        <code
                                            style="background:rgba(110,168,254,0.1);padding:2px 8px;border-radius:4px;font-size:12px;">
                                            {{ $col }}
                                        </code>
                                    </td>
                                    <td>
                                        @if ($req === 'Required')
                                            <span class="status-badge badge-danger">Required</span>
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

                <a href="{{ route('admin.employees.bulk-update.template') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download Bulk Update Template
                </a>
            </div>

            {{-- Upload Form --}}
            <div class="form-section anim-fade-up delay-2">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:linear-gradient(135deg,#F59E0B,#D97706);">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Upload Update File</div>
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

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <strong>Validation Error:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li style="font-size:13px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form method="POST" action="{{ route('admin.employees.bulk-update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select CSV or Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat me-2"></i> Update Employees
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
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

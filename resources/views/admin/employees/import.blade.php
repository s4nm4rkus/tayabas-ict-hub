@extends('layouts.admin-layout.admin')

@section('title', 'Import Employees')
@section('page-title', 'Import Employees')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Instructions --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="bi bi-info-circle"></i> Before You Import
                </div>
                <p style="font-size:14px;color:#555;">
                    Your CSV or Excel file must have these column headers in the first row:
                </p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" style="font-size:13px;">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th>Column Header</th>
                                <th>Required?</th>
                                <th>Example</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>last_name</td>
                                <td><span class="badge bg-danger">Required</span></td>
                                <td>dela Cruz</td>
                            </tr>
                            <tr>
                                <td>first_name</td>
                                <td><span class="badge bg-danger">Required</span></td>
                                <td>Juan</td>
                            </tr>
                            <tr>
                                <td>gov_email</td>
                                <td><span class="badge bg-danger">Required</span></td>
                                <td>juan@deped.gov.ph</td>
                            </tr>
                            <tr>
                                <td>birthdate</td>
                                <td><span class="badge bg-warning text-dark">Recommended</span></td>
                                <td>1990-01-15 (YYYY-MM-DD)</td>
                            </tr>
                            <tr>
                                <td>middle_name</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Santos</td>
                            </tr>
                            <tr>
                                <td>extension</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Jr.</td>
                            </tr>
                            <tr>
                                <td>gender</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Male / Female</td>
                            </tr>
                            <tr>
                                <td>place_of_birth</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Tayabas, Quezon</td>
                            </tr>
                            <tr>
                                <td>contact_num</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>09171234567</td>
                            </tr>
                            <tr>
                                <td>position</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Teacher I</td>
                            </tr>
                            <tr>
                                <td>employee_no</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>2025-001</td>
                            </tr>
                            <tr>
                                <td>philhealth</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>123456789</td>
                            </tr>
                            <tr>
                                <td>pagibig</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>123456789</td>
                            </tr>
                            <tr>
                                <td>tin</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>123-456-789</td>
                            </tr>
                            <tr>
                                <td>street</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>123 Rizal St.</td>
                            </tr>
                            <tr>
                                <td>barangay</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Poblacion</td>
                            </tr>
                            <tr>
                                <td>municipality</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Tayabas</td>
                            </tr>
                            <tr>
                                <td>province</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Quezon</td>
                            </tr>
                            <tr>
                                <td>region</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                                <td>Region IV-A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Updated note --}}
                <div class="alert alert-warning mt-3 mb-2" style="font-size:13px;">
                    <i class="bi bi-key me-1"></i>
                    <strong>Password Policy:</strong> Default password is auto-generated as
                    <code>FirstnameBirthdate</code> — example: <code>Juan01151990</code>.
                    If no birthdate is provided, fallback is <code>FirstnameICThub@123</code>.
                    <strong>Passwords will be shown once after import — save or print immediately.</strong>
                </div>

                <div class="alert alert-info mb-2" style="font-size:13px;">
                    <i class="bi bi-person-badge me-1"></i>
                    <strong>Employee ID</strong> (e.g. <code>ICTHUB-2026-0001</code>) is auto-assigned.
                    Employees must change their password on first login.
                </div>

                <a href="{{ route('admin.employees.import.template') }}" class="btn btn-outline-success btn-sm mt-1">
                    <i class="bi bi-download me-1"></i> Download Template
                </a>
            </div>

            {{-- Upload Form --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-upload"></i> Upload File
                </div>

                @if (session('import_errors'))
                    <div class="alert alert-warning">
                        <strong>Some rows were skipped:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach (session('import_errors') as $err)
                                <li style="font-size:13px;">{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.employees.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select CSV or Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">Max file size: 10MB. Accepted: .csv, .xlsx, .xls</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Import Employees
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <style>
        .info-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

@endsection

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Service Record</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1a1f2e;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0 4px;
            color: #1a1f2e;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .divider {
            border-top: 2px solid #1a1f2e;
            margin: 16px 0;
        }

        .cert-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 20px 0;
            text-transform: uppercase;
            color: #1a1f2e;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 160px;
            font-weight: bold;
            padding: 3px 0;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 10px;
        }

        .service-table th {
            background: #1a1f2e;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
        }

        .service-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .service-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .signature-block {
            margin-top: 40px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 40px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 11px;
            margin-top: 4px;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>REPUBLIC OF THE PHILIPPINES</h2>
        <h1>Schools Division Office — Tayabas City</h1>
        <p>Tayabas City, Quezon Province</p>
    </div>

    <div class="divider"></div>

    <div class="cert-title">Certificate of Service Record</div>

    {{-- Employee Info --}}
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ strtoupper($employee->full_name) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Position:</div>
            <div class="info-value">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Salary Grade:</div>
            <div class="info-value">{{ $employee->employment?->salary_grade ?? '—' }} / Step
                {{ $employee->employment?->salary_step ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nature of Appointment:</div>
            <div class="info-value">{{ $employee->employment?->nature_appoint ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Station:</div>
            <div class="info-value">{{ $employee->employment?->school_office_assign ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date of Original Appt:</div>
            <div class="info-value">
                {{ $employee->employment?->date_orig_appoint
                    ? \Carbon\Carbon::parse($employee->employment->date_orig_appoint)->format('F d, Y')
                    : '—' }}
            </div>
        </div>
    </div>

    {{-- Service Records Table --}}
    <table class="service-table">
        <thead>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Position</th>
                <th>Station</th>
                <th>SG</th>
                <th>Step</th>
                <th>Status</th>
                <th>Separation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employee->serviceRecords as $rec)
                <tr>
                    <td>{{ $rec->inclu_from?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ $rec->inclu_to?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ $rec->position ?? '—' }}</td>
                    <td>{{ $rec->station ?? '—' }}</td>
                    <td>{{ $rec->salary_grade ?? '—' }}</td>
                    <td>{{ $rec->salary_step ?? '—' }}</td>
                    <td>{{ $rec->service_status ?? '—' }}</td>
                    <td>{{ $rec->separation ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:12px;color:#888;">
                        No service records on file.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-block">
        <div class="signature-line"></div>
        <div class="signature-name">SCHOOLS DIVISION SUPERINTENDENT</div>
        <div class="signature-title">Schools Division Office — Tayabas City</div>
    </div>

    <div class="footer">
        Generated: {{ now()->format('F d, Y h:i A') }}
        &nbsp;|&nbsp; Request #{{ $certRequest->id }}
        &nbsp;|&nbsp; This is a system-generated document.
    </div>

</body>

</html>

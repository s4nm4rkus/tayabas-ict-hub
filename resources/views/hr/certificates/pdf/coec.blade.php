<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Employment with Compensation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 60px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1a1f2e;
        }

        .header h1 {
            font-size: 20px;
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
            margin: 20px 0;
        }

        .cert-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 30px 0;
            text-transform: uppercase;
            color: #1a1f2e;
        }

        .body-text {
            font-size: 12px;
            line-height: 2;
            text-align: justify;
            margin-bottom: 16px;
        }

        .highlight {
            font-weight: bold;
            text-transform: uppercase;
        }

        .salary-box {
            border: 1px solid #333;
            padding: 12px 20px;
            margin: 20px 0;
            background: #f9f9f9;
        }

        .salary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .signature-block {
            margin-top: 60px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 50px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 12px;
            margin-top: 4px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
        }

        .footer {
            text-align: center;
            margin-top: 60px;
            font-size: 10px;
            color: #999;
        }

        .issued-date {
            margin-top: 30px;
            font-size: 12px;
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

    <div class="cert-title">Certificate of Employment with Compensation</div>

    <div class="body-text">TO WHOM IT MAY CONCERN:</div>

    <div class="body-text">
        This is to certify that <span class="highlight">{{ $employee->full_name }}</span>
        is a bona fide employee of the Schools Division Office — Tayabas City,
        holding the position of
        <span class="highlight">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</span>.
    </div>

    <div class="salary-box">
        <div class="salary-row">
            <span>Salary Grade:</span>
            <span><strong>{{ $employee->employment?->salary_grade ?? '—' }}</strong></span>
        </div>
        <div class="salary-row">
            <span>Salary Step:</span>
            <span><strong>{{ $employee->employment?->salary_step ?? '—' }}</strong></span>
        </div>
        <div class="salary-row">
            <span>Nature of Appointment:</span>
            <span><strong>{{ $employee->employment?->nature_appoint ?? '—' }}</strong></span>
        </div>
        <div class="salary-row">
            <span>Status of Appointment:</span>
            <span><strong>{{ $employee->employment?->status_appoint ?? '—' }}</strong></span>
        </div>
        <div class="salary-row">
            <span>Date of Original Appointment:</span>
            <span><strong>
                    {{ $employee->employment?->date_orig_appoint
                        ? \Carbon\Carbon::parse($employee->employment->date_orig_appoint)->format('F d, Y')
                        : '—' }}
                </strong></span>
        </div>
    </div>

    <div class="body-text">
        This certification is being issued upon the request of the above-named employee
        for whatever legal purpose it may serve.
    </div>

    <div class="issued-date">
        Issued this <strong>{{ now()->format('jS') }}</strong> day of
        <strong>{{ now()->format('F Y') }}</strong>
        at Tayabas City, Quezon Province.
    </div>

    <div class="signature-block">
        <div class="signature-line"></div>
        <div class="signature-name">SCHOOLS DIVISION SUPERINTENDENT</div>
        <div class="signature-title">Schools Division Office — Tayabas City</div>
    </div>

    <div class="footer">
        Generated: {{ now()->format('F d, Y h:i A') }}
        &nbsp;|&nbsp; Request #{{ $certRequest->id }}
    </div>

</body>

</html>

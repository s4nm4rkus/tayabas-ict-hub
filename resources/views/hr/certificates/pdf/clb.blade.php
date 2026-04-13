<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Leave Balance</title>
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

        .leave-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .leave-table th {
            background: #1a1f2e;
            color: #fff;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }

        .leave-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
        }

        .leave-table tr:nth-child(even) td {
            background: #f8f9fa;
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

    <div class="cert-title">Certificate of Leave Balance</div>

    <div class="body-text">TO WHOM IT MAY CONCERN:</div>

    <div class="body-text">
        This is to certify that <span class="highlight">{{ $employee->full_name }}</span>,
        <span class="highlight">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</span>
        of this office, has the following leave balance as of
        <span class="highlight">{{ now()->format('F d, Y') }}</span>:
    </div>

    @php
        $leaveTypes = ['Vacation Leave', 'Sick Leave', 'Force Leave', 'Special Leave Benefits'];
        $approvedDays = [];
        foreach ($leaveTypes as $type) {
            $approvedDays[$type] = $employee->leaves
                ->where('leavetype', $type)
                ->where('leave_status', 'Approved')
                ->sum('total_days');
        }
        $totalPoints = $employee->points->sum('acc_points');
    @endphp

    <table class="leave-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Days Used</th>
                <th>Points Earned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypes as $type)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $approvedDays[$type] ?? 0 }}</td>
                    <td>{{ $type === 'Vacation Leave' ? round($totalPoints, 4) : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="body-text">
        Total Accumulated Leave Points:
        <span class="highlight">{{ round($totalPoints, 4) }}</span>
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

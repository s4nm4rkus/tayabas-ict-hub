<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
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
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
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
            margin: 6px 0;
            color: #1a1f2e;
        }

        .divider {
            border-top: 2px solid #1a1f2e;
            margin: 12px 0;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0f4c35;
            color: #fff;
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }

        tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 9px;
            color: #999;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .active {
            background: #d4edda;
            color: #155724;
        }

        .disabled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>REPUBLIC OF THE PHILIPPINES</h2>
        <h1>Schools Division Office — Tayabas City</h1>
    </div>
    <div class="divider"></div>
    <div class="title">Employee Master List</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Email</th>
                <th>SG</th>
                <th>Station</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $i => $emp)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $emp->user?->user_id ?? '—' }}</td>
                    <td style="font-weight:bold;">{{ $emp->full_name }}</td>
                    <td>{{ $emp->employment?->position ?? '—' }}</td>
                    <td>{{ $emp->gov_email }}</td>
                    <td>{{ $emp->employment?->salary_grade ?? '—' }}</td>
                    <td>{{ $emp->employment?->school_office_assign ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $emp->user?->user_stat === 'Enabled' ? 'active' : 'disabled' }}">
                            {{ $emp->user?->user_stat }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generated: {{ now()->format('F d, Y h:i A') }} &nbsp;|&nbsp;
        Total: {{ $employees->count() }} employees
    </div>
</body>

</html>

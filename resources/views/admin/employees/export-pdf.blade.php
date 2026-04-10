<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #1a1f2e;
            padding-bottom: 8px;
        }

        .header h2 {
            font-size: 13px;
            color: #1a1f2e;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 8px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1a1f2e;
            color: #fff;
        }

        thead th {
            padding: 3px 4px;
            text-align: left;
            font-size: 7px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody td {
            padding: 3px 4px;
            border-bottom: 1px solid #e9ecef;
            font-size: 8px;
            white-space: nowrap;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 7px;
        }

        .badge-disabled {
            background: #f8d7da;
            color: #721c24;
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 7px;
        }

        .footer {
            margin-top: 12px;
            font-size: 7px;
            color: #aaa;
            text-align: right;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Tayabas ICT Hub &mdash; Employee Information</h2>
        <p>
            Generated on {{ now()->format('F d, Y h:i A') }}
            &nbsp;&bull;&nbsp;
            Total: {{ $employees->count() }} employee(s)
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Ext.</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Place of Birth</th>
                <th>Contact No.</th>
                <th>Gov Email</th>
                <th>Emp No.</th>
                <th>PhilHealth</th>
                <th>Pag-IBIG</th>
                <th>TIN</th>
                <th>Municipality</th>
                <th>Province</th>
                <th>Region</th>
                <th>Position</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
                <tr>
                    <td>{{ $emp->user?->user_id ?? '—' }}</td>
                    <td>{{ $emp->last_name ?? '—' }}</td>
                    <td>{{ $emp->first_name ?? '—' }}</td>
                    <td>{{ $emp->middle_name ?? '—' }}</td>
                    <td>{{ $emp->ex_name ?? '—' }}</td>
                    <td>{{ $emp->gender ?? '—' }}</td>
                    <td>{{ $emp->birthdate?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ $emp->place_of_birth ?? '—' }}</td>
                    <td>{{ $emp->contact_num ?? '—' }}</td>
                    <td>{{ $emp->gov_email ?? '—' }}</td>
                    <td>{{ $emp->employee_no ?? '—' }}</td>
                    <td>{{ $emp->philhealth ?? '—' }}</td>
                    <td>{{ $emp->pagibig ?? '—' }}</td>
                    <td>{{ $emp->TIN ?? '—' }}</td>
                    <td>{{ $emp->municipality ?? '—' }}</td>
                    <td>{{ $emp->province ?? '—' }}</td>
                    <td>{{ $emp->region ?? '—' }}</td>
                    <td>{{ $emp->user?->user_pos ?? '—' }}</td>
                    <td>
                        @if ($emp->user?->user_stat === 'Enabled')
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-disabled">Disabled</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="19" style="text-align:center;padding:16px;color:#888;">
                        No employees found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Tayabas ICT Hub &mdash; Confidential &mdash; {{ now()->format('F d, Y') }}
    </div>

</body>

</html>

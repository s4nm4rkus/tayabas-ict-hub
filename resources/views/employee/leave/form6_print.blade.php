<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS Form No. 6 — {{ $leave->fullname }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-size: 9.5px !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            background: #f5f5f5;
        }

        .no-print {
            background: #1F2937;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
        }

        .no-print button {
            background: #34D399;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .no-print span {
            font-size: 13px;
            opacity: 0.8;
        }

        .print-wrapper {
            width: 210mm;
            margin: 60px auto 20px;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .print-wrapper {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
            }

            .page-break {
                page-break-after: always;
            }
        }

        /* ── Same styles as PDF blade ── */
        .form6-tag {
            /* position: absolute;
            top: 80px; */
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.4;
            margin-top: -20px;
            margin-bottom: 24px;
        }

        .department-header {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .department-header .col {
            display: table-cell;
            vertical-align: middle;
        }

        .department-header .col-logo {
            width: 80px;
            text-align: center;
        }

        .department-header .col-title {
            text-align: center;
        }

        .department-header .col-stamp {
            width: 130px;
            text-align: center;
        }

        .department-logo {
            width: 110px;
            height: auto;
        }

        .header-title p {
            font-size: 10px;
            font-style: italic;
            margin: 0;
            line-height: 1.5;
        }

        .header-title h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 4px 0 0;
        }

        .stamp-box {
            width: 110px;
            height: 40px;
            border: 1px dotted #000;
            font-size: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 4px;
            text-align: center;
        }

        .main-container {
            border: 1px solid #000;
            width: 100%;
        }

        .row-flex {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .row-flex .cell {
            display: table-cell;
            vertical-align: top;
            padding: 3px 4px;
            border-right: 1px solid #000;
            font-size: 9px;
        }

        .row-flex .cell:last-child {
            border-right: none;
        }

        .cell label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .cell .value {
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            min-height: 14px;
            padding: 1px 2px;
        }

        .section-title {
            text-align: center;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 3px 0;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .leave-ab-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .leave-a {
            display: table-cell;
            width: 52%;
            border-right: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            line-height: 1.3;
            font-size: 8px !important;
        }

        .leave-b {
            display: table-cell;
            width: 48%;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .leave-a label,
        .leave-b label {
            font-weight: bold;
            font-size: 9px;
            display: block;
            margin-bottom: 3px;
        }

        .check-item {
            margin-bottom: 2px;
            font-size: 9px;
        }

        .check-box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
            text-align: center;
            line-height: 9px;
            font-size: 8px;
            color: #000;
        }

        .check-box.checked {
            background: #ffffff;
            color: #000000;
            font-weight: bolder;
        }

        .check-ref {
            font-size: 8px;
            font-weight: normal;
            font-style: italic;
        }

        .detail-sub {
            font-size: 8.5px;
            font-style: italic;
            margin: 4px 0 2px;
        }

        .detail-value {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 100px;
            font-size: 9px;
            padding: 0 2px;
        }

        .cd-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .cd-c {
            display: table-cell;
            width: 55%;
            border-right: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .cd-d {
            display: table-cell;
            width: 45%;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .cd-c label,
        .cd-d label {
            font-weight: bold;
            font-size: 9px;
            display: block;
            margin-bottom: 3px;
        }

        .value-line {
            border-bottom: 1px solid #000;
            min-height: 13px;
            font-size: 10px;
            font-weight: bold;
            padding: 1px 2px;
            display: block;
        }

        .sig-block {
            text-align: center;
            padding: 4px;
        }

        .sig-name {
            font-family: 'Brush Script MT', 'Comic Sans MS', cursive;
            font-size: 13px;
            color: #1a1a6e;
        }

        .sig-position {
            font-size: 9px;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 60%;
            margin: 3px auto 2px;
        }

        .sig-label {
            font-size: 9px;
            font-weight: bold;
        }

        .sig-image {
            max-width: 120px;
            max-height: 50px;
            object-fit: contain;
            display: block;
            margin: 0 auto 2px;
        }

        .sec7-row {
            display: table;
            width: 100%;
        }

        .sec7-a {
            display: table-cell;
            width: 55%;
            border-right: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .sec7-b {
            display: table-cell;
            width: 45%;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .sec7-a label,
        .sec7-b label {
            font-weight: bold;
            font-size: 9px;
            display: block;
            margin-bottom: 3px;
        }

        .credits-table {
            width: 85%;
            border-collapse: collapse;
            font-size: 9px;
            margin: 4px auto;
        }

        .credits-table th,
        .credits-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: center;
        }

        .credits-table th {
            font-weight: bold;
            background: #f0f0f0;
        }

        .sec7cd-row {
            display: table;
            width: 100%;
            border-top: 2px solid #000;
        }

        .sec7-c {
            display: table-cell;
            width: 55%;
            border-right: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .sec7-d {
            display: table-cell;
            width: 45%;
            padding: 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .sec7-c label,
        .sec7-d label {
            font-weight: bold;
            font-size: 9px;
            display: block;
            margin-bottom: 3px;
        }

        .approved-line {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .approved-line .val {
            display: table-cell;
            width: 60px;
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding: 0 2px;
        }

        .approved-line .lbl {
            display: table-cell;
            padding-left: 4px;
            vertical-align: bottom;
        }

        .asds-sig-row {
            border-top: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
        }

        .sig-name-box {
            display: inline-block;
            border: 1px solid #000;
            padding: 4px 20px;
            text-align: center;
            min-width: 200px;
        }

        .page-two {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5px;
            color: #000;
        }

        .page-two .instructions-header {
            border: 1px solid #000;
            text-align: center;
            padding: 4px;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .page-two .instructions-body {
            display: table;
            width: 100%;
        }

        .page-two .col-left,
        .page-two .col-right {
            display: table-cell;
            width: 49%;
            vertical-align: top;
            padding-right: 6px;
            font-size: 9px;
            text-align: justify;
        }

        .page-two .col-right {
            padding-right: 0;
            padding-left: 6px;
        }

        .page-two p {
            margin: 0 0 4px;
            line-height: 1.4;
            text-align: justify;
        }

        .page-two .inst-title {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .page-two .inst-item {
            margin-left: 8px;
            margin-bottom: 4px;
        }

        .page-two .inst-sub {
            margin-left: 14px;
            margin-bottom: 2px;
        }

        .page-two .footer-note {
            font-size: 8.5px;
            margin-top: 8px;
            text-align: justify;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
    </style>
</head>

<body>

    {{-- ── Print Bar (hidden on print) ── --}}
    <div class="no-print">
        <button onclick="window.print()">
            <i>🖨️</i> Print Form 6
        </button>
        <span>CS Form No. 6 — {{ $leave->fullname }} — {{ $leave->date_applied?->format('M d, Y') }}</span>
    </div>

    <div class="print-wrapper">

        @php
            $leaveTypes = array_map('trim', explode(',', $leave->leave_types ?? ($leave->leavetype ?? '')));
            $details = $leave->leave_details ?? '';

            function isCheckedPrint(string $value, array $types): bool
            {
                return in_array($value, $types);
            }
            function detailContainsPrint(string $needle, string $haystack): bool
            {
                return stripos($haystack, $needle) !== false;
            }
            function detailValuePrint(string $prefix, string $haystack): string
            {
                if (preg_match('/' . preg_quote($prefix, '/') . '\s*:\s*([^;]+)/i', $haystack, $m)) {
                    return trim($m[1]);
                }
                return '';
            }
            $empSignature = $leave->employee_esign_path ? asset('storage/' . $leave->employee_esign_path) : null;
            $headSignature = $leave->head_esign_path ? asset('storage/' . $leave->head_esign_path) : null;
            $hrSignature = $leave->hr_esign_path ? asset('storage/' . $leave->hr_esign_path) : null;
            $aoSignature = $leave->ao_esign_path ? asset('storage/' . $leave->ao_esign_path) : null;
            $asdsSignature = $leave->asds_esign_path ? asset('storage/' . $leave->asds_esign_path) : null;
        @endphp

        {{-- ── Form 6 Tag ── --}}
        <div class="form6-tag">
            <p>Civil Service Form No. 6</p>
            <p>Revised 2020</p>
        </div>

        {{-- ── Header ── --}}
        <div class="department-header">
            <div class="col col-logo">
                <img src="{{ asset('storage/logo-nav.png') }}" alt="DepEd Logo" class="department-logo"
                    style="width: 70px !important;">
            </div>
            <div class="col col-title">
                <div class="header-title">
                    <p>Republic of the Philippines</p>
                    <p>(City Schools Division of the City of Tayabas)</p>
                    <p>(Brgy. Potol, Tayabas City)</p>
                    <h2>Application for Leave</h2>
                </div>
            </div>
            <div class="col col-stamp">
                <div class="stamp-box">Stamp of Date Receipt</div>
            </div>
        </div>

        <div class="main-container">

            {{-- Row 1 --}}
            <div class="row-flex">
                <div class="cell" style="width:28%;">
                    <label>1. Office/Department</label>
                    <div class="value">{{ $leave->department }}</div>
                </div>
                <div class="cell" style="width:24%;">
                    <label>2. Name <span style="font-weight:normal;font-size:8px;">(Last Name)</span></label>
                    <div class="value">{{ $leave->employee?->last_name }}</div>
                </div>
                <div class="cell" style="width:24%;">
                    <label style="font-size:8px;font-weight:normal;">(First Name)</label>
                    <div class="value">{{ $leave->employee?->first_name }}</div>
                </div>
                <div class="cell" style="width:24%;">
                    <label style="font-size:8px;font-weight:normal;">(Middle Name)</label>
                    <div class="value">{{ $leave->employee?->middle_name }}</div>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="row-flex">
                <div class="cell" style="width:33%;">
                    <label>3. Date of Filing</label>
                    <div class="value">{{ $leave->date_applied?->format('m/d/Y') }}</div>
                </div>
                <div class="cell" style="width:40%;">
                    <label>4. Position</label>
                    <div class="value">{{ $leave->position }}</div>
                </div>
                <div class="cell" style="width:27%;">
                    <label>5. Salary</label>
                    <div class="value">{{ $leave->salary }}</div>
                </div>
            </div>

            <div class="section-title">6. Details of Application</div>

            {{-- A & B --}}
            <div class="leave-ab-row">
                <div class="leave-a">
                    <label>6. A. TYPE OF LEAVE TO BE AVAILED OF</label>
                    @php
                        $leaveOptions = [
                            'Vacation Leave' => 'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Mandatory / Forced Leave' => 'Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Sick Leave' => 'Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Maternity Leave' => 'R.A. No. 11210 / IRR issued by CSC, DOLE and SSS',
                            'Paternity Leave' => 'R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended',
                            'Special Privilege Leave' => 'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Solo Parent Leave' => 'RA No. 8972 / CSC MC No. 8, s. 2004',
                            'Study Leave' => 'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            '10-Day VAWC Leave' => 'RA No. 9262 / CSC MC No. 15, s. 2005',
                            'Rehabilitation Privilege' => 'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Special Leave Benefits for Women' => 'RA No. 9710 / CSC MC No. 25, s. 2010',
                            'Special Emergency' => 'Calamity Leave (CSC MC No. 2, s. 2012, as amended)',
                            'Adoption Leave' => 'R.A. No. 8552',
                        ];
                    @endphp
                    @foreach ($leaveOptions as $value => $ref)
                        <div class="check-item">
                            <span class="check-box {{ isCheckedPrint($value, $leaveTypes) ? 'checked' : '' }}">
                                {!! isCheckedPrint($value, $leaveTypes) ? '✓' : '&nbsp;' !!}
                            </span>
                            {{ $value }}
                            <span class="check-ref">({{ $ref }})</span>
                        </div>
                    @endforeach
                    <div class="check-item">
                        <span class="check-box {{ isCheckedPrint('Others', $leaveTypes) ? 'checked' : '' }}">
                            {!! isCheckedPrint('Others', $leaveTypes) ? '✓' : '&nbsp;' !!}
                        </span>
                        Others: <span class="detail-value">{{ detailValuePrint('Others', $details) }}</span>
                    </div>
                </div>
                <div class="leave-b">
                    <label>B. DETAILS OF LEAVE</label>
                    <p class="detail-sub">In case of Vacation/Special Privilege Leave:</p>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Within Philippines', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Within Philippines', $details) ? '✓' : '&nbsp;' !!}</span>
                        Within the Philippines <span
                            class="detail-value">{{ detailValuePrint('Within Philippines', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Abroad', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Abroad', $details) ? '✓' : '&nbsp;' !!}</span>
                        Abroad (Specify) <span class="detail-value">{{ detailValuePrint('Abroad', $details) }}</span>
                    </div>
                    <p class="detail-sub">In case of Sick Leave:</p>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('In Hospital', $details) ? 'checked' : '' }}">{!! detailContainsPrint('In Hospital', $details) ? '✓' : '&nbsp;' !!}</span>
                        In Hospital (Specify Illness) <span
                            class="detail-value">{{ detailValuePrint('In Hospital', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Out Patient', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Out Patient', $details) ? '✓' : '&nbsp;' !!}</span>
                        Out Patient (Specify Illness) <span
                            class="detail-value">{{ detailValuePrint('Out Patient', $details) }}</span>
                    </div>
                    <p class="detail-sub">In case of Special Leave Benefits for Women:</p>
                    <div class="check-item">(Specify Illness) <span
                            class="detail-value">{{ detailValuePrint('Special Leave (Women)', $details) }}</span></div>
                    <p class="detail-sub">In case of Study Leave:</p>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Completion of Master', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Completion of Master', $details) ? '✓' : '&nbsp;' !!}</span>
                        Completion of Master's Degree
                    </div>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('BAR/Board', $details) ? 'checked' : '' }}">{!! detailContainsPrint('BAR/Board', $details) ? '✓' : '&nbsp;' !!}</span>
                        BAR/Board Examination Review
                    </div>
                    <p class="detail-sub">Other purpose:</p>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Monetization', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Monetization', $details) ? '✓' : '&nbsp;' !!}</span>
                        Monetization of Leave Credits
                    </div>
                    <div class="check-item">
                        <span
                            class="check-box {{ detailContainsPrint('Terminal Leave', $details) ? 'checked' : '' }}">{!! detailContainsPrint('Terminal Leave', $details) ? '✓' : '&nbsp;' !!}</span>
                        Terminal Leave
                    </div>
                </div>
            </div>

            {{-- C & D --}}
            <div class="cd-row">
                <div class="cd-c">
                    <label>6. C. NUMBER OF WORKING DAYS APPLIED FOR</label>
                    <span class="value-line">{{ $leave->number_of_days }}</span>
                    <label style="margin-top:4px;">INCLUSIVE DAYS</label>
                    <span class="value-line">{{ $leave->inclusive_dates }}</span>
                </div>
                <div class="cd-d">
                    <label>6. D. COMMUTATION</label>
                    <div class="check-item">
                        <span
                            class="check-box {{ $leave->commutation === 'Not Required' ? 'checked' : '' }}">{!! $leave->commutation === 'Not Required' ? '✓' : '&nbsp;' !!}</span>
                        Not Required
                    </div>
                    <div class="check-item">
                        <span
                            class="check-box {{ $leave->commutation === 'Required' ? 'checked' : '' }}">{!! $leave->commutation === 'Required' ? '✓' : '&nbsp;' !!}</span>
                        Required
                    </div>
                    <div class="sig-block" style="margin-top:6px;">
                        @if ($empSignature)
                            <img src="{{ $empSignature }}" class="sig-image" alt="Employee Signature">
                        @else
                            <div style="height:35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <div class="sig-label">(Signature of Applicant)</div>
                    </div>
                </div>
            </div>

            <div class="section-title">7. Details of Action on Application</div>

            {{-- 7A & 7B --}}
            <div class="sec7-row">
                <div class="sec7-a">
                    <label>7. A. CERTIFICATION OF LEAVE CREDITS</label>
                    <div style="margin:3px 0;font-size:9px;">As of <span
                            class="detail-value">{{ $leave->hr_as_of }}</span></div>
                    <table class="credits-table">
                        <tr>
                            <th style="width:40%;"></th>
                            <th>Vacation Leave</th>
                            <th>Sick Leave</th>
                        </tr>
                        <tr>
                            <td style="text-align:left;"><em>Total Earned</em></td>
                            <td>{{ $leave->vl_earned }}</td>
                            <td>{{ $leave->sl_earned }}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;"><em>Less this application</em></td>
                            <td>{{ $leave->vl_less }}</td>
                            <td>{{ $leave->sl_less }}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;"><em>Balance</em></td>
                            <td>{{ $leave->vl_balance }}</td>
                            <td>{{ $leave->sl_balance }}</td>
                        </tr>
                    </table>
                    <div class="sig-block" style="margin-top:6px;">
                        @if ($aoSignature)
                            <img src="{{ $aoSignature }}" class="sig-image">
                        @else
                            <div style="height:35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        @if ($leave->ao_esign_name)
                            <div style="font-size:9px;font-weight:bold;">{{ $leave->ao_esign_name }}</div>
                            <div class="sig-position">Administrative Officer</div>
                        @endif
                        <div class="sig-label">(Authorized Officer)</div>
                    </div>
                </div>
                <div class="sec7-b">
                    <label>7. B. RECOMMENDATION</label>
                    <div class="check-item">
                        <span
                            class="check-box {{ $leave->head_esign_name ? 'checked' : '' }}">{!! $leave->head_esign_name ? '✓' : '&nbsp;' !!}</span>
                        For Approval
                    </div>
                    <div class="check-item"><span class="check-box">&nbsp;</span> For disapproval due to</div>
                    <div style="border-bottom:1px solid #000;min-height:30px;margin:2px 0 4px 14px;"></div>
                    <div class="sig-block" style="margin-top:4px;">
                        @if ($headSignature)
                            <img src="{{ $headSignature }}" class="sig-image">
                        @else
                            <div style="height:35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        @if ($leave->head_esign_name)
                            <div style="font-size:9px;font-weight:bold;">{{ $leave->head_esign_name }}</div>
                        @endif
                        <div class="sig-label">(Authorized Officer)</div>
                    </div>
                </div>
            </div>

            {{-- 7C & 7D --}}
            <div class="sec7cd-row">
                <div class="sec7-c">
                    <label>7.C. APPROVED FOR:</label>
                    <div class="approved-line"><span class="val">{{ $leave->asds_days_with_pay }}</span><span
                            class="lbl">days with pay</span></div>
                    <div class="approved-line"><span class="val">{{ $leave->asds_days_without_pay }}</span><span
                            class="lbl">days without pay</span></div>
                    <div class="approved-line"><span class="val">{{ $leave->asds_others }}</span><span
                            class="lbl">others (specify)</span></div>
                </div>
                <div class="sec7-d">
                    <label>7.D. DISAPPROVED DUE TO:</label>
                    <div style="border-bottom:1px solid #000;min-height:40px;font-size:9px;padding:2px;">
                        {{ $leave->asds_disapproval }}</div>
                </div>
            </div>

            {{-- ASDS Signature --}}
            <div class="asds-sig-row">
                @if ($leave->asds_esign_name)
                    @php $asdsSig = $leave->asdsApprover?->e_signature ? asset('storage/'.$leave->asdsApprover->e_signature) : null; @endphp
                    @if ($asdsSignature)
                        <img src="{{ $asdsSignature }}" class="sig-image" style="max-width:140px;max-height:55px;">
                    @else<div class="sig-name" style="font-size:15px;">{{ $leave->asds_esign_name }}</div>
                    @endif
                    <div class="sig-name-box" style="margin-top:4px;">
                        <div style="font-size:10px;font-weight:bold;text-transform:uppercase;">
                            {{ $leave->asds_esign_name }}</div>
                        <div style="font-size:9px;">ASDS</div>
                    </div>
                @else
                    <div style="height:45px;"></div>
                    <div class="sig-name-box">
                        <div style="height:14px;"></div>
                    </div>
                @endif
                <div style="margin-top:4px;">
                    <div class="sig-line" style="width:250px;margin:0 auto;"></div>
                    <div class="sig-label">(Authorized Official)</div>
                </div>
            </div>

        </div>{{-- end main-container --}}

        {{-- Page Break --}}
        <div class="page-break"></div>

        {{-- Page 2: Instructions --}}
        <div class="page-two">
            <div class="instructions-header">INSTRUCTIONS AND REQUIREMENTS</div>
            <div class="instructions-body">
                <div class="col-left">
                    <p>Application for any type of leave shall <strong>be made on this Form and to be accomplished at
                            least
                            in duplicate</strong> with documentary requirements, as follows:</p>
                    <p class="inst-title">1. Vacation leave*</p>
                    <p class="inst-item">It shall be filed five (5) days in advance, whenever possible, of the
                        effective
                        date of such leave. Vacation leave within the Philippines or abroad shall be indicated in the
                        form
                        for purposes of securing travel authority and completing clearance from money and work
                        accountabilities.</p>
                    <p class="inst-title">2. Mandatory/Forced leave</p>
                    <p class="inst-item">Annual five-day vacation leave shall be forfeited if not taken during the
                        year. In
                        case the scheduled leave has been cancelled in the exigency of the service by the head of
                        agency, it
                        shall no longer be deducted from the accumulated vacation leave. Availment of one (1) day or
                        more
                        Vacation Leave (VL) shall be considered for complying the mandatory/forced leave subject to the
                        conditions under Section 25, Rule XVI of the Omnibus Rules Implementing E.O. No. 292.</p>
                    <p class="inst-title">3. Sick leave*</p>
                    <p class="inst-item">It shall be filed immediately upon employee's return from such leave.</p>
                    <p class="inst-item">If filed in advance or exceeding five (5) days, application shall be
                        accompanied
                        by a medical certificate. In case medical consultation was not availed of, an affidavit should
                        be
                        executed by an applicant.</p>
                    <p class="inst-title">4. Maternity leave* – 105 days</p>
                    <p class="inst-item">Proof of pregnancy e.g. ultrasound, doctor's certificate on the expected date
                        of
                        delivery</p>
                    <p class="inst-item">Accomplished Notice of Allocation of Maternity Leave Credits (CS Form No. 6a),
                        if
                        needed</p>
                    <p class="inst-item">Seconded female employees shall enjoy maternity leave with full pay in the
                        recipient agency.</p>
                    <p class="inst-title">5. Paternity leave – 7 days</p>
                    <p class="inst-item">Proof of child's delivery e.g. birth certificate, medical certificate and
                        marriage
                        contract</p>
                    <p class="inst-title">6. Special Privilege leave – 3 days</p>
                    <p class="inst-item">It shall be filed/approved for at least one (1) week prior to availment,
                        except on
                        emergency cases. Special privilege leave within the Philippines or abroad shall be indicated in
                        the
                        form for purposes of securing travel authority and completing clearance from money and work
                        accountabilities.</p>
                    <p class="inst-title">7. Solo Parent leave – 7 days</p>
                    <p class="inst-item">It shall be filed in advance or whenever possible five (5) days before going
                        on
                        such leave with updated Solo Parent Identification Card.</p>
                    <p class="inst-title">8. Study leave* – up to 6 months</p>
                    <p class="inst-item">Shall meet the agency's internal requirements, if any;</p>
                    <p class="inst-item">Contract between the agency head or authorized representative and the employee
                        concerned.</p>
                    <p class="inst-title">9. VAWC leave – 10 days</p>
                    <p class="inst-item">It shall be filed in advance or immediately upon the woman employee's return
                        from
                        such leave.</p>
                    <p class="inst-item">It shall be accompanied by any of the following supporting documents:</p>
                    <p class="inst-sub">a. Barangay Protection Order (BPO) obtained from the barangay;</p>
                    <p class="inst-sub">b. Temporary/Permanent Protection Order (TPO/PPO) obtained from the court;</p>
                    <p class="inst-sub">c. If the protection order is not yet issued by the barangay or the court, a
                        certification issued by the Punong Barangay/Kagawad or Prosecutor or the Clerk of Court that the
                        application for the BPO, ______________________</p>
                </div>
                <div class="col-right">
                    <p class="inst-sub" style="margin-top:0;">TPO or PPO has been filed with the said office shall be
                        sufficient to support the application for the ten-day leave; or</p>
                    <p class="inst-sub">d. In the absence of the BPO/TPO/PPO or the certification, a police report
                        specifying the details of the occurrence of violence on the victim and a medical certificate may
                        be
                        considered, at the discretion of the immediate supervisor of the woman employee concerned.</p>
                    <p class="inst-title">10. Rehabilitation leave* – up to 6 months</p>
                    <p class="inst-item">Application shall be made within one (1) week from the time of the accident
                        except
                        when a longer period is warranted.</p>
                    <p class="inst-item">Letter request supported by relevant reports such as the police report, if
                        any,
                    </p>
                    <p class="inst-item">Medical certificate on the nature of the injuries, the course of treatment
                        involved, and the need to undergo rest, recuperation, and rehabilitation, as the case may be</p>
                    <p class="inst-item">Written concurrence of a government physician should be obtained relative to
                        the
                        recommendation for rehabilitation if the attending physician is a private practitioner,
                        particularly
                        on the duration of the period of rehabilitation.</p>
                    <p class="inst-title">11. Special leave benefits for women* – up to 2 months</p>
                    <p class="inst-item">The application may be filed in advance, that is, at least five (5) days prior
                        to
                        the scheduled date of the gynecological surgery that will be undergone by the employee. In case
                        of
                        emergency, the application for special leave shall be filed immediately upon employee's return
                        but
                        during confinement the agency shall be notified of said surgery.</p>
                    <p class="inst-item">The application shall be accompanied by a medical certificate filled out by
                        the
                        proper medical authorities, e.g. the attending surgeon accompanied by a clinical summary
                        reflecting
                        the gynecological disorder which shall be addressed or was addressed by the said surgery; the
                        histopathological report; the operative technique used for the surgery; the duration of the
                        surgery
                        including the perioperative period (period of confinement around surgery); as well as the
                        employee's
                        estimated period of recuperation for the same.</p>
                    <p class="inst-title">12. Special Emergency (Calamity) leave – up to 5 days</p>
                    <p class="inst-item">The special emergency leave can be applied for a maximum of five (5) straight
                        working days or staggered basis within thirty (30) days from the actual occurrence of the
                        natural
                        calamity/disaster. Said privilege shall be enjoyed once a year, not in every instance of
                        calamity or
                        disaster.</p>
                    <p class="inst-item">The head of office shall take full responsibility for the grant of special
                        emergency leave and verification of the employee's eligibility to be granted thereof.</p>
                    <p class="inst-title">13. Monetization of leave credits</p>
                    <p class="inst-item">Application for monetization of fifty percent (50%) or more of the accumulated
                        leave credits shall be accompanied by letter request to the head of the agency stating the valid
                        and
                        justifiable reasons.</p>
                    <p class="inst-title">14. Terminal leave*</p>
                    <p class="inst-item">Proof of employee's resignation or retirement or separation from the service.
                    </p>
                    <p class="inst-title">15. Adoption Leave</p>
                    <p class="inst-item">Application for adoption leave shall be filed with an authenticated copy of
                        the
                        Pre-Adoptive Placement Authority issued by the Department of Social Welfare and Development
                        (DSWD).
                    </p>
                    <div class="footer-note">
                        *For leave of absence for thirty (30) calendar days or more and terminal leave, application
                        shall be
                        accompanied by a clearance from money, property and work-related accountabilities (pursuant to
                        CSC
                        Memorandum Circular No. 2, s. 1985).
                    </div>
                </div>
            </div>

        </div>{{-- end print-wrapper --}}

        <script>
            window.onafterprint = () => {};
        </script>
</body>

</html>

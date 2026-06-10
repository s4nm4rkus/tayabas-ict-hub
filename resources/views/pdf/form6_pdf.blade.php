<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CS Form No. 6 - Application for Leave</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }


        * {
            box-sizing: border-box;
            /* margin: 0; */
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            background: #fff;
            margin: 15mm;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .form6-tag {
            /* position: absolute;
            top: 80px; */
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            line-height: 0.5;
            margin-top: -30px;
            margin-bottom: 24px;
        }

        /* Real tables everywhere — prevents DomPDF "Frame not found in cellmap" */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-container {
            border: 1px solid #000;
            width: 100%;
        }

        .cell-pad {
            padding: 3px 4px;
        }

        .cell-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .cell-value {
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

        .check-item {
            margin-bottom: 2px;
            font-size: 9px;
        }

        /* Use a plain bordered box — DomPDF renders this reliably */
        .check-box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
            text-align: center;
            line-height: 8px;
            font-size: 9px;
            font-weight: bold;
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
            min-width: 80px;
            font-size: 9px;
            padding: 0 2px;
        }

        .value-line {
            border-bottom: 1px solid #000;
            min-height: 13px;
            font-size: 10px;
            font-weight: bold;
            padding: 1px 2px;
            display: block;
            margin-bottom: 4px;
        }

        .credits-table {
            width: 90%;
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

        .sig-block {
            text-align: center;
            padding: 4px 4px 2px;
        }

        .sig-image {
            max-width: 110px;
            max-height: 45px;
            display: block;
            margin: 0 auto 2px;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 3px auto 2px;
        }

        .sig-name {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sig-position {
            font-size: 8px;
        }

        .sig-label {
            font-size: 9px;
            font-weight: bold;
        }

        .sig-name-box {
            display: inline-block;
            border: 1px solid #000;
            padding: 4px 20px;
            text-align: center;
            min-width: 180px;
        }

        .stamp-box {
            border: 1px dotted #000;
            font-size: 9px;
            text-align: center;
            padding: 8px 4px;
            width: 110px;
        }

        .approved-line-val {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 50px;
            font-weight: bold;
            padding: 0 2px;
        }

        /* Page 2 */
        .page-two {
            font-size: 9px;
        }

        .instructions-header {
            border: 1px solid #000;
            text-align: center;
            padding: 4px;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .inst-col {
            width: 49%;
            vertical-align: top;
            padding-right: 6px;
            font-size: 9px;
        }

        .inst-col-right {
            padding-right: 0;
            padding-left: 6px;
        }

        .page-two p {
            margin: 0 0 4px;
            line-height: 1.4;
        }

        .inst-title {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .inst-item {
            margin-left: 8px;
            margin-bottom: 4px;
        }

        .inst-sub {
            margin-left: 14px;
            margin-bottom: 2px;
        }

        .footer-note {
            font-size: 8.5px;
            margin-top: 8px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
    </style>
</head>

<body>

    @php
        $leaveTypes = array_map('trim', explode(',', $leave->leave_types ?? ($leave->leavetype ?? '')));
        $details = $leave->leave_details ?? '';

        function detailContains(string $needle, string $haystack): bool
        {
            return stripos($haystack, $needle) !== false;
        }
        function detailValue(string $prefix, string $haystack): string
        {
            if (preg_match('/' . preg_quote($prefix, '/') . '\s*:\s*([^;]+)/i', $haystack, $m)) {
                return trim($m[1]);
            }
            return '';
        }

        // ── Images: use asset() URLs — DomPDF resolves these correctly
        //    when chroot is set to public_path() in your Dompdf options.
        //    The key fix: same approach as the print blade.
        $logoUrl = asset('storage/logo-nav.png');

        // Employee sig — from live relationship (same as print blade)
        $empSigUrl = $leave->employee?->user?->e_signature
            ? asset('storage/' . $leave->employee->user->e_signature)
            : null;

        // Approver sigs — prefer snapshotted path on leave record,
        // fall back to live relationship (mirrors print blade logic)
        $headSigUrl = null;
        if ($leave->head_esign_path) {
            $headSigUrl = asset('storage/' . $leave->head_esign_path);
        } elseif ($leave->deptHead?->e_signature) {
            $headSigUrl = asset('storage/' . $leave->deptHead->e_signature);
        }

        $hrSigUrl = null;
        if ($leave->hr_esign_path) {
            $hrSigUrl = asset('storage/' . $leave->hr_esign_path);
        } elseif ($leave->hrApprover?->e_signature) {
            $hrSigUrl = asset('storage/' . $leave->hrApprover->e_signature);
        }

        $aoSigUrl = null;
        if ($leave->ao_esign_path) {
            $aoSigUrl = asset('storage/' . $leave->ao_esign_path);
        } elseif ($leave->aoApprover?->e_signature) {
            $aoSigUrl = asset('storage/' . $leave->aoApprover->e_signature);
        }

        $asdsSigUrl = null;
        if ($leave->asds_esign_path) {
            $asdsSigUrl = asset('storage/' . $leave->asds_esign_path);
        } elseif ($leave->asdsApprover?->e_signature) {
            $asdsSigUrl = asset('storage/' . $leave->asdsApprover->e_signature);
        }

        // Checkmark: use plain "X" — avoids DomPDF UTF-8 question-mark bug
        // If your DomPDF has a Unicode font loaded, swap 'X' back to '&#x2713;'
        $chk = '/';
    @endphp

    {{-- ══ PAGE 1 ══ --}}

    <div class="form6-tag">
        <p>Civil Service Form No. 6</p>
        <p>Revised 2020</p>
    </div>

    {{-- Header --}}
    <table style="margin-bottom:6px;">
        <tr>
            <td style="width:80px;text-align:center;vertical-align:middle;">
                <img src="{{ $logoUrl }}" style="width:65px;height:auto;" alt="Logo">
            </td>
            <td style="text-align:center;vertical-align:middle;">
                <p style="font-size:10px;font-style:italic;margin:0;line-height:1.5;">Republic of the Philippines</p>
                <p style="font-size:10px;font-style:italic;margin:0;line-height:1.5;">City Schools Division of the City
                    of Tayabas</p>
                <p style="font-size:10px;font-style:italic;margin:0;line-height:1.5;">Brgy. Potol, Tayabas City</p>
                <p style="font-size:13px;font-weight:bold;text-transform:uppercase;margin:4px 0 0;">Application for
                    Leave</p>
            </td>
            <td style="width:130px;text-align:right;vertical-align:middle;">
                <div class="stamp-box">Stamp of Date Receipt</div>
            </td>
        </tr>
    </table>

    <div class="main-container">

        {{-- Row 1: Department + Name --}}
        <table style="border-bottom:1px solid #000;">
            <tr>
                <td class="cell-pad" style="width:28%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">1. Office/Department</span>
                    <div class="cell-value">{{ $leave->department }}</div>
                </td>
                <td class="cell-pad" style="width:24%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">2. Name (Last Name)</span>
                    <div class="cell-value">{{ $leave->employee?->last_name ?? '' }}</div>
                </td>
                <td class="cell-pad" style="width:24%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label" style="font-weight:normal;">(First Name)</span>
                    <div class="cell-value">{{ $leave->employee?->first_name ?? '' }}</div>
                </td>
                <td class="cell-pad" style="width:24%;vertical-align:top;">
                    <span class="cell-label" style="font-weight:normal;">(Middle Name)</span>
                    <div class="cell-value">{{ $leave->employee?->middle_name ?? '' }}</div>
                </td>
            </tr>
        </table>

        {{-- Row 2: Date + Position + Salary --}}
        <table style="border-bottom:1px solid #000;">
            <tr>
                <td class="cell-pad" style="width:33%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">3. Date of Filing</span>
                    <div class="cell-value">{{ $leave->date_applied?->format('m/d/Y') }}</div>
                </td>
                <td class="cell-pad" style="width:40%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">4. Position</span>
                    <div class="cell-value">{{ $leave->position }}</div>
                </td>
                <td class="cell-pad" style="width:27%;vertical-align:top;">
                    <span class="cell-label">5. Salary</span>
                    <div class="cell-value">{{ $leave->salary }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">6. Details of Application</div>

        {{-- 6A & 6B --}}
        <table style="border-bottom:1px solid #000;">
            <tr>
                <td class="cell-pad" style="width:52%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">6. A. TYPE OF LEAVE TO BE AVAILED OF</span>
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
                            <span class="check-box">{{ in_array($value, $leaveTypes) ? $chk : '' }}</span>
                            {{ $value }} <span class="check-ref">({{ $ref }})</span>
                        </div>
                    @endforeach
                    <div class="check-item">
                        <span class="check-box">{{ in_array('Others', $leaveTypes) ? $chk : '' }}</span>
                        Others: <span class="detail-value">{{ detailValue('Others', $details) }}</span>
                    </div>
                </td>

                <td class="cell-pad" style="width:48%;vertical-align:top;">
                    <span class="cell-label">B. DETAILS OF LEAVE</span>

                    <p class="detail-sub">In case of Vacation/Special Privilege Leave:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Within Philippines', $details) ? $chk : '' }}</span>
                        Within the Philippines <span
                            class="detail-value">{{ detailValue('Within Philippines', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Abroad', $details) ? $chk : '' }}</span>
                        Abroad (Specify) <span class="detail-value">{{ detailValue('Abroad', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Sick Leave:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('In Hospital', $details) ? $chk : '' }}</span>
                        In Hospital (Specify Illness) <span
                            class="detail-value">{{ detailValue('In Hospital', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Out Patient', $details) ? $chk : '' }}</span>
                        Out Patient (Specify Illness) <span
                            class="detail-value">{{ detailValue('Out Patient', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Special Leave Benefits for Women:</p>
                    <div class="check-item">
                        (Specify Illness) <span
                            class="detail-value">{{ detailValue('Special Leave (Women)', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Study Leave:</p>
                    <div class="check-item">
                        <span
                            class="check-box">{{ detailContains('Completion of Master', $details) ? $chk : '' }}</span>
                        Completion of Master's Degree
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('BAR/Board', $details) ? $chk : '' }}</span>
                        BAR/Board Examination Review
                    </div>

                    <p class="detail-sub">Other purpose:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Monetization', $details) ? $chk : '' }}</span>
                        Monetization of Leave Credits
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Terminal Leave', $details) ? $chk : '' }}</span>
                        Terminal Leave
                    </div>
                </td>
            </tr>
        </table>

        {{-- 6C & 6D --}}
        <table style="border-bottom:1px solid #000;">
            <tr>
                <td class="cell-pad" style="width:55%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">6. C. NUMBER OF WORKING DAYS APPLIED FOR</span>
                    <span class="value-line">{{ $leave->number_of_days }}</span>
                    <span class="cell-label" style="margin-top:4px;">INCLUSIVE DATES</span>
                    <span class="value-line">{{ $leave->inclusive_dates }}</span>
                </td>
                <td class="cell-pad" style="width:45%;vertical-align:top;">
                    <span class="cell-label">6. D. COMMUTATION</span>
                    <div class="check-item">
                        <span class="check-box">{{ $leave->commutation === 'Not Required' ? $chk : '' }}</span>
                        Not Required
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ $leave->commutation === 'Required' ? $chk : '' }}</span>
                        Required
                    </div>
                    {{-- Employee Signature --}}
                    <div class="sig-block" style="margin-top:6px;">
                        @if ($empSigUrl)
                            <img src="{{ $empSigUrl }}" class="sig-image" alt="Employee Signature">
                        @else
                            <div style="height:35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <div class="sig-label">(Signature of Applicant)</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">7. Details of Action on Application</div>

        {{-- 7A & 7B --}}
        <table style="border-bottom:1px solid #000;">
            <tr>
                {{-- 7A: Leave Credits + HR Sig --}}
                <td class="cell-pad" style="width:55%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">7. A. CERTIFICATION OF LEAVE CREDITS</span>
                    <p style="font-size:9px;margin:3px 0;">
                        As of <span class="detail-value">{{ $leave->hr_as_of }}</span>
                    </p>
                    <table class="credits-table">
                        <tr>
                            <th style="width:40%;text-align:left;"></th>
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
                    {{-- AO Signature --}}
                    @if ($leave->ao_esign_name)
                        <div class="sig-block">
                            @if ($aoSigUrl)
                                <img src="{{ $aoSigUrl }}" class="sig-image" alt="AO Signature">
                            @else
                                <div style="height:35px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $leave->ao_esign_name }}</div>
                            <div class="sig-position">Administrative Officer</div>
                            <div class="sig-label">(Authorized Officer)</div>
                        </div>
                    @endif
                </td>

                {{-- 7B: Recommendation + Head Sig + AO Sig --}}
                <td class="cell-pad" style="width:45%;vertical-align:top;">
                    <span class="cell-label">7. B. RECOMMENDATION</span>
                    <div class="check-item" style="margin-bottom:4px;">
                        <span class="check-box">{{ $leave->head_esign_name ? $chk : '' }}</span>
                        For Approval
                    </div>
                    <div class="check-item">
                        <span class="check-box"></span>
                        For disapproval due to
                    </div>
                    <div style="border-bottom:1px solid #000;min-height:28px;margin:2px 0 4px 14px;"></div>
                    {{-- Head Signature --}}
                    <div class="sig-block" style="margin-top:4px;">
                        @if ($headSigUrl)
                            <img src="{{ $headSigUrl }}" class="sig-image" alt="Head Signature">
                        @else
                            <div style="height:35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        @if ($leave->head_esign_name)
                            <div class="sig-name">{{ $leave->head_esign_name }}</div>
                            <div class="sig-position">Department Head</div>
                        @endif
                        <div class="sig-label">(Authorized Officer)</div>
                    </div>

                </td>
            </tr>
        </table>

        {{-- 7C & 7D --}}
        <table style="border-top:2px solid #000;">
            <tr>
                <td class="cell-pad" style="width:55%;border-right:1px solid #000;vertical-align:top;">
                    <span class="cell-label">7.C. APPROVED FOR:</span>
                    <p style="margin-bottom:3px;">
                        <span class="approved-line-val">{{ $leave->asds_days_with_pay }}</span> days with pay
                    </p>
                    <p style="margin-bottom:3px;">
                        <span class="approved-line-val">{{ $leave->asds_days_without_pay }}</span> days without pay
                    </p>
                    <p style="margin-bottom:3px;">
                        <span class="approved-line-val">{{ $leave->asds_others }}</span> others (specify)
                    </p>
                </td>
                <td class="cell-pad" style="width:45%;vertical-align:top;">
                    <span class="cell-label">7.D. DISAPPROVED DUE TO:</span>
                    <div style="border-bottom:1px solid #000;min-height:40px;font-size:9px;padding:2px;">
                        {{ $leave->asds_disapproval }}
                    </div>
                </td>
            </tr>
        </table>


        {{-- ASDS Signature --}}
        <table style="border-top:1px solid #000;width:100%; ">

            <tr>
                <td style="text-align:center;padding:0; margin-bottom: -20px; display: block;">
                    {{-- Signature image FIRST --}}
                    @if ($asdsSigUrl)
                        <img src="{{ $asdsSigUrl }}"
                            style="max-width:140px;max-height:55px;display:block;margin:0 auto 4px;"
                            alt="ASDS Signature">
                    @else
                        <div style="height:55px;">&nbsp;</div>
                    @endif
                </td>
                <td style="text-align:center;padding:6px 4px; display: block;">
                    {{-- Name box AFTER --}}
                    <div class="sig-name-box" style="margin-top:4px;">
                        @if ($leave->asds_esign_name)
                            <div style="font-size:10px;font-weight:bold;text-transform:uppercase;">
                                {{ $leave->asds_esign_name }}
                            </div>
                            <div style="font-size:9px;">ASDS</div>
                        @else
                            <div style="height:14px;">&nbsp;</div>
                        @endif
                    </div>

                    <div style="margin-top:4px;">
                        <div style="border-top:1px solid #000;width:250px;margin:0 auto 2px;"></div>
                        <div class="sig-label">(Authorized Official)</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>{{-- end .main-container --}}

    <div class="page-break"></div>

    {{-- ══ PAGE 2: INSTRUCTIONS ══ --}}
    <div class="page-two">
        <div class="instructions-header">INSTRUCTIONS AND REQUIREMENTS</div>
        <table>
            <tr>
                <td class="inst-col">
                    <p>Application for any type of leave shall <strong>be made on this Form and to be accomplished at
                            least in duplicate</strong> with documentary requirements, as follows:</p>
                    <p class="inst-title">1. Vacation leave*</p>
                    <p class="inst-item">It shall be filed five (5) days in advance, whenever possible, of the
                        effective date of such leave. Vacation leave within the Philippines or abroad shall be indicated
                        in the form for purposes of securing travel authority and completing clearance from money and
                        work accountabilities.</p>
                    <p class="inst-title">2. Mandatory/Forced leave</p>
                    <p class="inst-item">Annual five-day vacation leave shall be forfeited if not taken during the
                        year. In case the scheduled leave has been cancelled in the exigency of the service by the head
                        of agency, it shall no longer be deducted from the accumulated vacation leave. Availment of one
                        (1) day or more Vacation Leave (VL) shall be considered for complying the mandatory/forced leave
                        subject to the conditions under Section 25, Rule XVI of the Omnibus Rules Implementing E.O. No.
                        292.</p>
                    <p class="inst-title">3. Sick leave*</p>
                    <p class="inst-item">It shall be filed immediately upon employee's return from such leave.</p>
                    <p class="inst-item">If filed in advance or exceeding five (5) days, application shall be
                        accompanied by a medical certificate. In case medical consultation was not availed of, an
                        affidavit should be executed by an applicant.</p>
                    <p class="inst-title">4. Maternity leave* - 105 days</p>
                    <p class="inst-item">Proof of pregnancy e.g. ultrasound, doctor's certificate on the expected date
                        of delivery</p>
                    <p class="inst-item">Accomplished Notice of Allocation of Maternity Leave Credits (CS Form No. 6a),
                        if needed</p>
                    <p class="inst-item">Seconded female employees shall enjoy maternity leave with full pay in the
                        recipient agency.</p>
                    <p class="inst-title">5. Paternity leave - 7 days</p>
                    <p class="inst-item">Proof of child's delivery e.g. birth certificate, medical certificate and
                        marriage contract</p>
                    <p class="inst-title">6. Special Privilege leave - 3 days</p>
                    <p class="inst-item">It shall be filed/approved for at least one (1) week prior to availment,
                        except on emergency cases. Special privilege leave within the Philippines or abroad shall be
                        indicated in the form for purposes of securing travel authority and completing clearance from
                        money and work accountabilities.</p>
                    <p class="inst-title">7. Solo Parent leave - 7 days</p>
                    <p class="inst-item">It shall be filed in advance or whenever possible five (5) days before going
                        on such leave with updated Solo Parent Identification Card.</p>
                    <p class="inst-title">8. Study leave* - up to 6 months</p>
                    <p class="inst-item">Shall meet the agency's internal requirements, if any;</p>
                    <p class="inst-item">Contract between the agency head or authorized representative and the employee
                        concerned.</p>
                    <p class="inst-title">9. VAWC leave - 10 days</p>
                    <p class="inst-item">It shall be filed in advance or immediately upon the woman employee's return
                        from such leave.</p>
                    <p class="inst-item">It shall be accompanied by any of the following supporting documents:</p>
                    <p class="inst-sub">a. Barangay Protection Order (BPO) obtained from the barangay;</p>
                    <p class="inst-sub">b. Temporary/Permanent Protection Order (TPO/PPO) obtained from the court;</p>
                    <p class="inst-sub">c. If the protection order is not yet issued by the barangay or the court, a
                        certification issued by the Punong Barangay/Kagawad or Prosecutor or the Clerk of Court that the
                        application for the BPO, ______________________</p>
                </td>
                <td class="inst-col inst-col-right">
                    <p class="inst-sub" style="margin-top:0;">TPO or PPO has been filed with the said office shall be
                        sufficient to support the application for the ten-day leave; or</p>
                    <p class="inst-sub">d. In the absence of the BPO/TPO/PPO or the certification, a police report
                        specifying the details of the occurrence of violence on the victim and a medical certificate may
                        be considered, at the discretion of the immediate supervisor of the woman employee concerned.
                    </p>
                    <p class="inst-title">10. Rehabilitation leave* - up to 6 months</p>
                    <p class="inst-item">Application shall be made within one (1) week from the time of the accident
                        except when a longer period is warranted.</p>
                    <p class="inst-item">Letter request supported by relevant reports such as the police report, if
                        any,</p>
                    <p class="inst-item">Medical certificate on the nature of the injuries, the course of treatment
                        involved, and the need to undergo rest, recuperation, and rehabilitation, as the case may be</p>
                    <p class="inst-item">Written concurrence of a government physician should be obtained relative to
                        the recommendation for rehabilitation if the attending physician is a private practitioner,
                        particularly on the duration of the period of rehabilitation.</p>
                    <p class="inst-title">11. Special leave benefits for women* - up to 2 months</p>
                    <p class="inst-item">The application may be filed in advance, that is, at least five (5) days prior
                        to the scheduled date of the gynecological surgery that will be undergone by the employee. In
                        case of emergency, the application for special leave shall be filed immediately upon employee's
                        return but during confinement the agency shall be notified of said surgery.</p>
                    <p class="inst-item">The application shall be accompanied by a medical certificate filled out by
                        the proper medical authorities, e.g. the attending surgeon accompanied by a clinical summary
                        reflecting the gynecological disorder which shall be addressed or was addressed by the said
                        surgery; the histopathological report; the operative technique used for the surgery; the
                        duration of the surgery including the perioperative period (period of confinement around
                        surgery); as well as the employee's estimated period of recuperation for the same.</p>
                    <p class="inst-title">12. Special Emergency (Calamity) leave - up to 5 days</p>
                    <p class="inst-item">The special emergency leave can be applied for a maximum of five (5) straight
                        working days or staggered basis within thirty (30) days from the actual occurrence of the
                        natural calamity/disaster. Said privilege shall be enjoyed once a year, not in every instance of
                        calamity or disaster.</p>
                    <p class="inst-item">The head of office shall take full responsibility for the grant of special
                        emergency leave and verification of the employee's eligibility to be granted thereof.</p>
                    <p class="inst-title">13. Monetization of leave credits</p>
                    <p class="inst-item">Application for monetization of fifty percent (50%) or more of the accumulated
                        leave credits shall be accompanied by letter request to the head of the agency stating the valid
                        and justifiable reasons.</p>
                    <p class="inst-title">14. Terminal leave*</p>
                    <p class="inst-item">Proof of employee's resignation or retirement or separation from the service.
                    </p>
                    <p class="inst-title">15. Adoption Leave</p>
                    <p class="inst-item">Application for adoption leave shall be filed with an authenticated copy of
                        the Pre-Adoptive Placement Authority issued by the Department of Social Welfare and Development
                        (DSWD).</p>
                    <div class="footer-note">
                        *For leave of absence for thirty (30) calendar days or more and terminal leave, application
                        shall be accompanied by a clearance from money, property and work-related accountabilities
                        (pursuant to CSC Memorandum Circular No. 2, s. 1985).
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>

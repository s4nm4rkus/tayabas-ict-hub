<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Service Record</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Bookman Old Style", "Book Antiqua", Georgia, serif;
            font-size: 10pt;
            color: #000;
            padding: 15px 80px;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 12px;
            margin-bottom: 4px;
        }

        .header-logo {
            height: 70px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            flex: 1;
            line-height: 1.35;
        }

        .header-text .republic {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-text .dept {
            font-size: 13pt;
            font-weight: bold;
        }

        .header-text .region {
            font-size: 9.5pt;
            font-weight: bold;
        }

        .header-text .division {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* ── DIVIDERS ── */
        .divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }

        .divider-thin {
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        /* ── DOCUMENT TITLE ── */
        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 10px 0 2px;
            text-transform: uppercase;
        }

        .doc-subtitle {
            text-align: center;
            font-size: 9pt;
            font-style: italic;
            margin-bottom: 10px;
        }

        /* ── BP LINE ── */
        .bp-line {
            font-size: 9.5pt;
            margin-bottom: 6px;
        }

        /* ── INFO TABLES ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 9pt;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: bottom;
        }

        .info-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .info-underline {
            border-bottom: 1px solid #000;
            min-width: 120px;
            display: inline-block;
            text-align: center;
            padding-bottom: 1px;
            font-weight: bold;
        }

        .info-caption {
            font-size: 7pt;
            text-align: center;
            font-style: italic;
            color: #333;
            margin-top: 1px;
        }

        /* ── CERT TEXT ── */
        .cert-text {
            font-size: 9.5pt;
            line-height: 1.65;
            text-align: justify;
            margin: 8px 0;
        }

        /* ── SERVICE RECORD TABLE ── */
        .sr-outer {
            border: 1.5px solid #000;
            margin: 10px auto;
            width: 100%;
        }

        .sr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            table-layout: auto;
        }

        .sr-table th {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 7.5pt;
            background: #ececec;
            vertical-align: middle;
            line-height: 1.3;
        }

        .sr-table td {
            border: 1px solid #000;
            padding: 3px 6px;
            text-align: center;
            font-size: 8pt;
            vertical-align: middle;
            word-break: break-word;
        }

        .sr-table td.left {
            text-align: left;
        }

        .sr-table tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        .sr-table tfoot td {
            font-weight: bold;
            font-size: 7.5pt;
            background: #f0f0f0;
            text-align: left;
            padding: 3px 6px;
        }

        /* ── COMPLIANCE NOTE ── */
        .compliance {
            font-size: 8.5pt;
            line-height: 1.65;
            text-align: justify;
            margin: 8px 0;
        }

        /* ── SIGNATURE SECTION ── */
        .sig-section {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sig-date-box {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 3px;
            width: 190px;
            text-align: center;
            font-size: 8.5pt;
        }

        .sig-right {
            text-align: center;
            font-size: 9pt;
        }

        .sig-right .certified {
            margin-bottom: 28px;
            font-size: 9pt;
        }

        .sig-name-block {
            border-top: 1px solid #000;
            width: 210px;
            margin: 0 auto;
            padding-top: 3px;
        }

        .sig-name {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
        }

        .sig-title {
            font-size: 8.5pt;
        }

        /* ── FOOTER ── */
        @page {
            margin-bottom: 120px;
        }

        .footer-div {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0 80px 10px 80px;
        }


        .ref-line {
            font-size: 8pt;
            font-style: italic;
            color: #444;
            line-height: 1.5;
        }

        .footer-img {
            width: 90%;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        /* ── PAGE NUMBERS ── */
        .page-number::after {
            content: counter(page);
        }

        .page-count::after {
            content: counter(pages);
        }
    </style>
</head>

<body>

    {{-- ═══ HEADER ═══ --}}
    <div class="header">
        <img class="header-logo" src="{{ public_path('storage/deped-seal.png') }}" alt="DepEd Seal">
        <div class="header-text">
            <div style="line-height: normal; padding: 0; margin: 0; font-size: 12pt; font-family:'OldEnglish', serif;">
                Republic
                of the Philippines</div>
            <div style="line-height: 90%; padding: 0; margin: 0;font-size: 18pt; font-family:'OldEnglish', serif">
                Department of Education
            </div>
            <div class="region">REGION IV-A CALABARZON</div>
            <div class="division">City Schools Division of the City of Tayabas</div>
        </div>

    </div>

    <div class="divider"></div>

    {{-- ═══ DOCUMENT TITLE ═══ --}}
    <div class="doc-title">Service Record</div>
    <div class="doc-subtitle">(To be accomplished by employer)</div>

    {{-- ═══ PHP VARIABLES ═══ --}}
    @php
        $empNo = $employee->employee_no ?? '—';
        $gender = strtolower($employee->gender ?? 'male');
        $pronoun = $gender === 'female' ? 'her' : 'his';
        $station = $employee->employment?->school_office_assign ?? '—';
        $born = $employee->birthdate ? \Carbon\Carbon::parse($employee->birthdate)->format('F d, Y') : '—';
        $bplace = $employee->place_of_birth ?? '—';
        $records = $employee->serviceRecords()->orderBy('inclu_from', 'asc')->get();
    @endphp

    {{-- ═══ BP LINE ═══ --}}
    <div class="bp-line"><strong>BP#&nbsp;{{ $empNo }}</strong></div>

    {{-- ═══ NAME ROW ═══ --}}
    <table class="info-table">
        <tr>
            <td style="width:55px;  padding-bottom: 4px;" class="info-label">NAME:</td>
            <td style="width:190px;">
                <span class="info-underline" style="min-width:160px;">{{ strtoupper($employee->last_name) }}</span>
                <div class="info-caption">(Surname)</div>
            </td>
            <td style="width:190px;">
                <span class="info-underline" style="min-width:160px;">{{ strtoupper($employee->first_name) }}</span>
                <div class="info-caption">(Given Name)</div>
            </td>
            <td style="width:110px;">
                <span class="info-underline"
                    style="min-width:90px;">{{ strtoupper($employee->middle_name ?? '') }}</span>
                <div class="info-caption">(Middle Name)</div>
            </td>
            <td style="font-size:7.5pt; color:#555; padding-left:8px;">
                @if ($gender === 'female')
                    (If married woman, give full maiden name)
                @endif
            </td>
        </tr>
    </table>

    {{-- ═══ BIRTH ROW ═══ --}}
    <table class="info-table">
        <tr>
            <td style="width:55px;" class="info-label">BIRTH:</td>
            <td style="width:190px;">
                <span class="info-underline" style="min-width:160px;">{{ $born }}</span>
                <div class="info-caption">(Date)</div>
            </td>
            <td style="width:190px;">
                <span class="info-underline" style="min-width:160px;">{{ $bplace }}</span>
                <div class="info-caption">(Place)</div>
            </td>
            <td colspan="2" style="font-size:7.5pt; color:#555; padding-left:8px;">
                Date herein should be checked from birth or baptismal certificate or some other reliable document.
            </td>
        </tr>
    </table>

    <div class="divider-thin"></div>

    {{-- ═══ CERTIFICATION TEXT ═══ --}}
    <div class="cert-text">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the employee named above actually rendered service
        in this Office as shown by {{ $pronoun }} service record below. Each line of which is supported by
        appointment and other papers actually issued by this Office and approved by the authorities concerned:
    </div>

    {{-- ═══ SERVICE RECORD TABLE ═══ --}}
    <div class="sr-outer">
        <table class="sr-table">
            <thead>
                <tr>
                    <th colspan="2">Service<br>(Inclusive Dates)</th>
                    <th colspan="3">Records of Appointment</th>
                    <th colspan="2">Office / Entity<br>Division / Station</th>
                    <th>Separation /<br>LWOP</th>
                </tr>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Salary</th>
                    <th>Office / Station</th>
                    <th>Branch</th>
                    <th>Separation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr>
                        <td>{{ $rec->inclu_from ? \Carbon\Carbon::parse($rec->inclu_from)->format('n/j/Y') : '—' }}</td>
                        <td>{{ $rec->inclu_to ? \Carbon\Carbon::parse($rec->inclu_to)->format('n/j/Y') : 'To Date' }}
                        </td>
                        <td class="left">{{ $rec->designation ?? ($rec->position ?? '—') }}</td>
                        <td>{{ $rec->service_status ?? '—' }}</td>
                        <td>
                            @php
                                $salaryVal = $rec->salary_grade
                                    ? \App\Models\Salary::where('salary_grade', $rec->salary_grade)->value(
                                        'step_' . ($rec->salary_step ?? 1),
                                    )
                                    : null;
                            @endphp
                            {{ $salaryVal ? number_format((float) $salaryVal, 2) : '—' }}
                        </td>
                        <td class="left">{{ $rec->station ?? $station }}</td>
                        <td>{{ $rec->branch ?? 'National' }}</td>
                        <td>{{ $rec->separation ?? 'NONE' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:12px; color:#888; font-style:italic;">
                            No service records on file.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($records->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="8">★ STILL IN THE SERVICE TO DATE</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    {{-- ═══ COMPLIANCE NOTE ═══ --}}
    <div class="compliance">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued in compliance with Executive Order No. 54, dated August 10, 1954
        in accordance with Circular Number 58, dated August 10, 1954 of the System.
    </div>

    {{-- ═══ SIGNATURE BLOCK ═══ --}}
    <div class="sig-section">
        <div class="sig-left">
            <div class="sig-date-box">
                {{ now()->format('F d, Y') }}<br><em>Date</em>
            </div>
        </div>
        <div class="sig-right">
            <div class="certified">Certified Correct:</div>
            <div class="sig-name-block">
                <div class="sig-name">CONRADO C. GABARDA</div>
                <div class="sig-title">Administrative Officer V</div>
            </div>
        </div>
    </div>

    {{-- ═══ FOOTER (fixed — repeats on every page) ═══ --}}
    <div class="footer-div">
        <div class="ref-row" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div class="ref-line">
                AS-RO / Service Record &mdash;
                {{ strtoupper($employee->last_name) }}, {{ strtoupper(substr($employee->first_name, 0, 1)) }}.
                <div class="ref-line" style="position: absolute; right: 80px; ">
                    Page <span class="page-number"></span> of <span class="page-count"></span>
                </div>
            </div>

        </div>
        <div style="border-top: 2px solid #000; margin: 6px 0;"></div>
        <div style="text-align: center;">
            <img class="footer-img" src="{{ public_path('storage/pdffooter-logo.png') }}" alt="DepEd Footer">
        </div>
    </div>

</body>

</html>

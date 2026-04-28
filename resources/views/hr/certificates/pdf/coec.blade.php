<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        body {
            font-family: "Bookman Old Style", serif;
            font-size: 11pt;
            color: #000;
            padding: 15px 80px;
        }

        @font-face {
            font-family: 'OldEnglish';
            src: url({{ storage_path('fonts/oldenglishtextmt.ttf') }}) format("truetype");
        }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 4px;
            text-align: center;
        }

        .header-logo {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text .region {
            font-size: 10pt;
            font-weight: bold;
        }

        .header-text .division {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .divider {
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        .cert-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 20px 0;
        }

        .salutation {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .body-text {
            font-size: 11pt;
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 14px;
        }

        .comp-table {
            width: 70%;
            border-collapse: collapse;
            margin: 16px 0 0 100px;
            font-size: 11pt;
        }

        .comp-table td {
            padding: 5px 12px;
        }

        .comp-table td:first-child {
            width: 70%;
            font-weight: bold;
        }

        .comp-table td:last-child {
            text-align: right;
            width: 30%;
        }

        .comp-table tr.total td {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .issue-line {
            margin: 20px 0;
            font-size: 11pt;
            line-height: 1.8;
            text-align: justify;
        }

        .signature-block {
            margin-top: 40px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .signature-title {
            font-size: 10pt;
        }

        .footer-div {
            position: absolute;
            bottom: 80px;
            left: 0;
            right: 0;
            padding: 0 40px;
        }

        .footer-img {
            width: 90%;
            display: block;
            margin: 0 auto;
        }

        .ref-line {
            font-size: 9pt;
            font-family: "Bookman Old Style", serif;
            font-style: italic;
            color: #555;
            margin-top: 6px;
            text-align: left;
        }

        .highlight {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header text-center">
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

    {{-- CERT TITLE --}}
    <div class="cert-title">Certificate of Employment and Compensation</div>

    {{-- SALUTATION --}}
    <div class="salutation">To whom it may concern:</div>

    {{-- BODY --}}
    <div class="body-text">
        <span style="padding-left: 50px;">This is to certify that</span> <span
            class="highlight">{{ $employee->full_name }}</span>
        is a <span class="highlight">{{ $employee->employment?->nature_appoint ?? 'permanent' }}</span>
        <span class="highlight">{{ $employee->employment?->position ?? $employee->user?->user_pos }}</span>
        at City Schools Division of the City of Tayabas. This further certifies that
        {{ $employee->gender === 'Female' ? 'she' : 'he' }} is receiving the following remuneration, to wit:
    </div>

    {{-- COMPENSATION TABLE --}}
    @php
        $sg = $employee->employment?->salary_grade;
        $step = $employee->employment?->salary_step ?? 1;
        $salary = \App\Models\Salary::where('salary_grade', $sg)->first();
        $stepCol = 'step_' . $step;
        $annualBasic = $salary ? ($salary->$stepCol ?? 0) * 12 : 0;

        // Standard DepEd allowances
        $pera = 24000;
        $clothing = 6000;
        $thirteenth = $annualBasic / 12;
        $fourteenth = $annualBasic / 12;
        $cashGift = 5000;
        $pei = 5000;
        $total = $annualBasic + $pera + $clothing + $thirteenth + $fourteenth + $cashGift + $pei;
    @endphp
    <table class="comp-table">
        <tr>
            <td>Annual Basic Salary</td>
            <td>P{{ number_format($annualBasic, 2) }}</td>
        </tr>
        <tr>
            <td>ACA/PERA</td>
            <td>P{{ number_format($pera, 2) }}</td>
        </tr>
        <tr>
            <td>Clothing Allowance</td>
            <td>P{{ number_format($clothing, 2) }}</td>
        </tr>
        <tr>
            <td>13th Month Pay</td>
            <td>P{{ number_format($thirteenth, 2) }}</td>
        </tr>
        <tr>
            <td>14th Month Pay</td>
            <td>P{{ number_format($fourteenth, 2) }}</td>
        </tr>
        <tr>
            <td>Cash Gift</td>
            <td>P{{ number_format($cashGift, 2) }}</td>
        </tr>
        <tr>
            <td>Productivity Enhancement Incentive</td>
            <td>P{{ number_format($pei, 2) }}</td>
        </tr>
        <tr class="total">
            <td>TOTAL</td>
            <td>P{{ number_format($total, 2) }}</td>
        </tr>
    </table>

    {{-- ISSUE LINE --}}
    <div class="issue-line">
        Issued upon request of
        {{ $employee->gender === 'Female' ? 'Ms.' : 'Mr.' }}
        <span class="highlight">{{ $employee->last_name }}</span>
        this <strong>{{ now()->format('jS') }}</strong> day of
        <strong>{{ now()->format('F Y') }}</strong>
        for whatever legal purpose this may serve.
    </div>

    {{-- SIGNATURE --}}
    <div class="signature-block" style="text-align: right; align-self: flex-end;">
        <div class="signature-name">CONRADO C. GABARDA</div>
        <div class="signature-title" style="margin-right: 30px">Administrative Officer V</div>
    </div>




    {{-- FOOTER BANNER --}}
    <div class="footer-div" style="position: absolute; bottom: 10px; left: 0; right: 0; padding: 0 80px;">
        <div class="ref-line" style="margin-top: 50px; text-align: left;">
            HRMO / Cert. of Employment and Compensation &nbsp;
        </div>
        <div class="ref-line" style="margin-top: 0px; margin-bottom: 0; text-align: left;">
            HRC-COEC-{{ now()->format('m-d-Y') }}
        </div>
        <div style="border-top: 2px solid #000; margin: 8px 0;"></div>
        <div style="text-align: center;">
            <img class="footer-img" src="{{ public_path('storage/pdffooter-logo.png') }}" alt="DepEd Footer">
        </div>
    </div>
</body>

</html>

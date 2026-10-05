{{-- resources/views/hr/attendance/pdf.blade.php --}}
{{-- CSC Form No. 48 — Daily Time Record --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
        }

        .page {
            width: 100%;
            padding: 24px;
            page-break-after: always;
        }

        .page-back {
            width: 100%;
            padding: 24px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 6px;
            padding: 4px 0 6px;
            border-bottom: 1.5px solid #000;
        }

        .form-header .form-no {
            font-size: 8pt;
            margin-bottom: 2px;
        }

        .form-header h1 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 2px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
        }

        .info-table td {
            padding: 2px 4px;
            font-size: 8.5pt;
            vertical-align: bottom;
        }

        .info-table .value-filled {
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding-left: 4px;
        }

        .hours-row {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0 8px;
            font-size: 8pt;
        }

        .hours-row td {
            padding: 2px 4px;
            vertical-align: bottom;
        }

        .hours-row .hours-value {
            border-bottom: 1px solid #000;
            min-width: 80px;
            padding-left: 4px;
            font-weight: bold;
        }

        table.dtr {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        table.dtr th,
        table.dtr td {
            border: 0.75px solid #000;
            text-align: center;
            padding: 1.5px 2px;
        }

        table.dtr thead th {
            background: #fff;
            font-weight: bold;
            font-size: 7.5pt;
            line-height: 1.2;
        }

        table.dtr .col-day {
            width: 24px;
            font-weight: bold;
        }

        table.dtr .col-time {
            width: 46px;
            font-size: 7.5pt;
        }

        table.dtr .col-ut {
            width: 32px;
            font-size: 7.5pt;
        }

        table.dtr tr.weekend td,
        table.dtr tr.holiday td {
            background: #f2f2f2;
            color: #555;
            font-style: italic;
        }

        table.dtr tr.total-row td {
            font-weight: bold;
            background: #f9f9f9;
            border-top: 1.5px solid #000;
        }

        table.dtr td.remark-cell {
            font-size: 7pt;
            text-align: left;
            padding-left: 3px;
            font-style: italic;
            color: #555;
        }

        .cert-block {
            margin-top: 10px;
            font-size: 8pt;
            line-height: 1.5;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .sig-table td {
            width: 50%;
            padding: 0 8px;
            vertical-align: bottom;
        }

        .sig-line {
            border-top: 1px solid #000;
            padding-top: 3px;
            text-align: center;
            font-size: 8pt;
            margin-top: 28px;
        }

        .sig-note {
            text-align: center;
            font-size: 7.5pt;
            margin-top: 2px;
            color: #333;
        }

        .instructions-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 14px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 6px;
        }

        .instructions-body {
            font-size: 8.5pt;
            line-height: 1.7;
            text-align: justify;
        }

        .instructions-body p {
            margin-bottom: 10px;
        }

        .instructions-body .note {
            font-size: 8pt;
            margin-top: 10px;
            border-top: 0.5px solid #aaa;
            padding-top: 8px;
        }

        .form-no-back {
            text-align: right;
            font-size: 8pt;
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>

<body>

    @php
        /**
         * Safe 12-hour time formatter.
         * Accepts H:i string, Carbon object, or null.
         * Defined as a plain function — works reliably inside DomPDF.
         */
        function dtrFmt($t)
        {
            if (!$t || $t === '—') {
                return '';
            }
            try {
                return \Carbon\Carbon::parse($t)->format('h:i A');
            } catch (\Exception $e) {
                return '';
            }
        }

        // Use the pre-computed totals passed from the controller.
        // These were calculated by recomputing from actual time data,
        // so they are always accurate regardless of DB column state.
        $totalUtMins = (int) ($dtr['total_undertime'] ?? 0);
        $totalUtHours = intdiv($totalUtMins, 60);
        $totalUtRemMins = $totalUtMins % 60;
        $totalHours = (float) ($dtr['total_hours'] ?? 0);
        $totalLateMins = (int) ($dtr['total_late'] ?? 0);
    @endphp

    {{-- ══════════════════ PAGE 1 — DTR ══════════════════ --}}
    <div class="page">
        <div style="width:45%;margin-left:25%;border:1px solid #000;">

            <div class="form-header">
                <div class="form-no">Civil Service Form No. 48</div>
                <h1>DAILY TIME RECORD</h1>
            </div>

            <table class="info-table">
                <tr>
                    <td style="white-space:nowrap;">Name:</td>
                    <td class="value-filled" colspan="3">
                        {{ strtoupper($dtr['employee']->last_name) }},
                        {{ strtoupper($dtr['employee']->first_name) }}
                    </td>
                </tr>
            </table>

            <table class="info-table">
                <tr>
                    <td style="white-space:nowrap;">For the month of</td>
                    <td class="value-filled">{{ strtoupper($dtr['month']) }}</td>
                </tr>
            </table>

            <table class="info-table">
                <tr>
                    <td>Official hours of arrival and departure</td>
                </tr>
            </table>
            <table class="hours-row">
                <tr>
                    <td>Regular days:</td>
                    <td class="hours-value"></td>
                    <td style="padding-left:12px;">Saturdays:</td>
                    <td class="hours-value"></td>
                </tr>
            </table>

            <table class="dtr">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-day">Day</th>
                        <th colspan="2">A.M.</th>
                        <th colspan="2">P.M.</th>
                        <th colspan="2">UNDER TIME</th>
                    </tr>
                    <tr>
                        <th class="col-time">Arrival</th>
                        <th class="col-time">Departure</th>
                        <th class="col-time">Arrival</th>
                        <th class="col-time">Departure</th>
                        <th class="col-ut">Hours</th>
                        <th class="col-ut">Minutes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dtr['days'] as $day)
                        @php
                            $utMins = (int) ($day['undertime'] ?? 0);
                            $utHrs = intdiv($utMins, 60);
                            $utMin = $utMins % 60;
                            $dateObj = \Carbon\Carbon::parse($day['date']);
                            $isSat = $dateObj->isSaturday();
                            $isSun = $dateObj->isSunday();
                            $rowClass = $day['is_holiday'] ? 'holiday' : ($day['is_weekend'] ? 'weekend' : '');
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td class="col-day">{{ $day['day'] }}</td>

                            @if ($isSat)
                                <td colspan="4" class="remark-cell">Saturday</td>
                                <td class="col-ut"></td>
                                <td class="col-ut"></td>
                            @elseif ($isSun)
                                <td colspan="4" class="remark-cell">Sunday</td>
                                <td class="col-ut"></td>
                                <td class="col-ut"></td>
                            @elseif ($day['is_holiday'])
                                <td colspan="4" class="remark-cell">{{ $day['holiday_name'] ?? 'Holiday' }}</td>
                                <td class="col-ut"></td>
                                <td class="col-ut"></td>
                            @else
                                {{--
                                Four separate columns:
                                AM Arrival   = am_time_in   (first punch of the morning)
                                AM Departure = am_time_out  (last punch before noon — usually null for ZKTeco)
                                PM Arrival   = pm_time_in   (first punch after lunch — usually null for ZKTeco)
                                PM Departure = pm_time_out  (last punch of the day)

                                For ZKTeco single in/out: am_time_in has the morning punch,
                                pm_time_out has the afternoon punch.
                                am_time_out and pm_time_in will be blank (device doesn't track lunch break).
                            --}}
                                <td class="col-time">
                                    {{ !$day['absent'] ? dtrFmt($day['am_time_in']) : '' }}
                                </td>
                                <td class="col-time">
                                    {{ !$day['absent'] ? dtrFmt($day['am_time_out']) : '' }}
                                </td>
                                <td class="col-time">
                                    {{ !$day['absent'] ? dtrFmt($day['pm_time_in']) : '' }}
                                </td>
                                <td class="col-time">
                                    {{ !$day['absent'] ? dtrFmt($day['pm_time_out']) : '' }}
                                </td>
                                {{-- Undertime hours --}}
                                <td class="col-ut">
                                    @if (!$day['absent'] && $utMins > 0)
                                        {{ $utHrs }}
                                    @endif
                                </td>
                                {{-- Undertime minutes --}}
                                <td class="col-ut">
                                    @if (!$day['absent'] && $utMins > 0)
                                        {{ str_pad($utMin, 2, '0', STR_PAD_LEFT) }}
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- TOTAL row — from pre-computed controller values --}}
                    <tr class="total-row">
                        <td colspan="5" style="text-align:right;padding-right:5px;">TOTAL</td>
                        <td class="col-ut">
                            {{-- @if ($totalUtMins > 0)
                                {{ $totalUtHours }}
                            @endif --}}
                        </td>
                        <td class="col-ut">
                            {{-- @if ($totalUtMins > 0)
                                {{ str_pad($totalUtRemMins, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        </td> --}}
                    </tr>
                </tbody>
            </table>

            <div class="cert-block">
                <p style="padding:4px;">
                    I certify on my honor that the above is a true and correct report of the hours of work
                    performed, record of which was made daily at the time of arrival and departure from office.
                </p>
            </div>

            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-note">Signature of Personnel</div>
                    </td>
                </tr>
            </table>

            <table class="sig-table">
                <tr>
                    <td>
                        <div style="font-size:8pt;margin-bottom:4px;">VERIFIED as to the prescribed office hours:</div>
                        <div class="sig-line"></div>
                        <div class="sig-note">In Charge</div>
                    </td>
                </tr>
            </table>

            <p style="text-align:center;font-size:7.5pt;margin-top:14px;color:#555;padding-bottom:4px;">
                (SEE INSTRUCTION ON BACK)
            </p>
        </div>
    </div>

    {{-- ══════════════════ PAGE 2 — INSTRUCTIONS ══════════════════ --}}
    <div class="page-back">
        <div style="width:43%;margin-left:25%;border:1px solid #000;padding:8px 8px 24px 8px;">

            <div class="form-no-back">Civil Service Form No. 48</div>
            <div class="instructions-title">I N S T R U C T I O N S</div>

            <div class="instructions-body">
                <p>
                    Civil Service Form No. 48, after completion, should be filed in the records of the Bureau or
                    Office which submits the monthly report on Civil Service Form No. 3 to the Bureau of Civil Service.
                </p>
                <p>
                    In lieu of the above, court interpreters and stenographers who accompany the judges of the Court
                    of First Instance will fill out the daily time reports on this form in triplicate, after which
                    they should be approved by the judge with whom service has been rendered, or by an officer of the
                    Department of Justice authorized to do so. The original should be forwarded promptly after the end
                    of the month to the Bureau of Civil Service, thru the Department of Justice; the duplicate to be
                    kept in the Department of Justice; and the triplicate, in the office of the Clerk of Court where
                    service was rendered.
                </p>
                <p>
                    In the space provided for the purpose on the other side will be indicated the office hours the
                    employee is required to observe, as for example, "Regular days, 8:00 to 12:00 and 1:00 to 4:00;
                    Saturdays 8:00 to 1:00."
                </p>
                <p>
                    Attention is invited to paragraph 3, Civil Service Rule XV, Executive Order No. 5, series of 1909,
                    which reads as follows:
                </p>
                <p style="margin-left:16px;">
                    "Each chief of a Bureau or Office shall require a daily record of attendance of all the officers
                    and employees under him entitled to leave of absence or vacation (including teachers) to be kept
                    on the proper form and also a systematic office record showing for each day all absences from duty
                    from any cause whatever. At the beginning of each month he shall report to the Commissioner on the
                    proper form of all absences from any cause whatever, including the exact amount of undertime of
                    each person for each day. Officers or employees serving in the field or on the water need not be
                    required to keep a daily record, but all absences of such employees must be included in the monthly
                    report of changes and absences. Falsification of time records will render the offending officer or
                    employee liable to summary removal from the service and criminal prosecution."
                </p>
                <div class="note">
                    <strong>NOTE</strong> – A record made from memory at sometime subsequent to the occurrence of an
                    event is not reliable. Non-observance of office hours deprives the employee of the leave privileges
                    although he may have rendered overtime service. Where service is rendered outside of the Office for
                    the whole morning or afternoon, notation to that effect should be made clearly.
                </div>
            </div>
        </div>
    </div>

</body>

</html>

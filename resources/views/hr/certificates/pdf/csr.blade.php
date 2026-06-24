<x-pdf.certificates.layout-csr title="Service Record" subtitle="(To be accomplished by employer)">
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

    {{-- ═══ FOOTER ═══ --}}
    @slot('footer')
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
    @endslot
</x-pdf.certificates.layout-csr>

<x-pdf.certificates.layout title="Certificate of Employment and Compensation">
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

    {{-- Footer Slot --}}
    @slot('footer')
        <x-pdf.certificates.footer footer-title="Certificate of Employment and Compensation" footer-code="HRC-COEC" />
    @endslot
</x-pdf.certificates.layout>

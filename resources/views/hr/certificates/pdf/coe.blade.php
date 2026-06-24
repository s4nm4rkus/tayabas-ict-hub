<x-pdf.certificates.layout title="Certificate of Employment">
    <div class="body-text">
        To whom it may concern:
    </div>

    <div class="body-text" style="text-indent: 50px;">
        This is to certify that <span class="highlight">{{ $employee->full_name }}</span>,
        is a bona fide employee of the Schools Division Office — Tayabas City,
        holding the position of <span
            class="highlight">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</span>,
        with Salary Grade <span class="highlight">{{ $employee->employment?->salary_grade ?? '—' }}</span>,
        Step <span class="highlight">{{ $employee->employment?->salary_step ?? '—' }}</span>.
    </div>

    <div class="body-text" style="text-indent: 50px;">
        This employee has been in the service since
        <span class="highlight">
            {{ $employee->employment?->date_orig_appoint
                ? \Carbon\Carbon::parse($employee->employment->date_orig_appoint)->format('F d, Y')
                : '—' }}
        </span>
        under a
        <span class="highlight">{{ $employee->employment?->nature_appoint ?? '—' }}</span>
        appointment, with status
        <span class="highlight">{{ $employee->employment?->status_appoint ?? '—' }}</span>.
    </div>

    <div class="body-text" style="text-indent: 50px;">
        This certification is being issued upon the request of the above-named employee
        for whatever legal purpose it may serve.
    </div>

    <div class="issue-line" style="text-indent: 50px;">
        Issued this <strong>{{ now()->format('jS') }}</strong> day of
        <strong>{{ now()->format('F Y') }}</strong>
        at Tayabas City, Quezon Province.
    </div>


    {{-- SIGNATURE --}}
    <div class="signature-block" style="text-align: right; align-self: flex-end;">
        <div class="signature-name">CONRADO C. GABARDA</div>
        <div class="signature-title" style="margin-right: 30px">Administrative Officer V</div>
    </div>

    {{-- <div class="signature-block">
        <div class="signature-line"></div>
        <div class="signature-name">SCHOOLS DIVISION SUPERINTENDENT</div>
        <div class="signature-title">Schools Division Office — Tayabas City</div>
    </div>

    <div class="footer">
        This document is computer-generated and valid without signature unless otherwise stated.
        &nbsp;|&nbsp; Generated: {{ now()->format('F d, Y h:i A') }}
        &nbsp;|&nbsp; Request #{{ $certRequest->id }}
    </div> --}}

    @slot('footer')
        <x-pdf.certificates.footer footer-title="Cert. of Employment" footer-code="HRC-COE" />
    @endslot
</x-pdf.certificates.layout>

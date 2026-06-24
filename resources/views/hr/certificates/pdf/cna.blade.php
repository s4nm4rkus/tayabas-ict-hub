<x-pdf.certificates.layout title="CERTIFICATION">


    <div class="body-text">To whom it may concern:</div>

    <div class="body-text" style="text-indent: 50px;">
        This is to certify that <span class="highlight">{{ $employee->full_name }}</span>,
        <span class="highlight">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</span>
        of this office,
        <span style="font-weight: bold;"> has no leave of absences without pay for the past five (5) months</span> as of
        this date.
    </div>
    <div class="issued-date" style="text-indent: 50px">
        Issued upon request of Ms/Mr. {{ $employee->last_name }} this <strong>{{ now()->format('jS') }}</strong> day of
        <strong>{{ now()->format('F Y') }}</strong> for whatever legal
        purpose this may serve.
    </div>

    {{-- SIGNATURE --}}
    <div class="signature-block" style="text-align: right; align-self: flex-end;">
        <div class="signature-name">CONRADO C. GABARDA</div>
        <div class="signature-title" style="margin-right: 30px">Administrative Officer V</div>
    </div>

    @slot('footer')
        <x-pdf.certificates.footer footer-title="Certificate of No Absences" footer-code="HRC-CNA" />
    @endslot

</x-pdf.certificates.layout>

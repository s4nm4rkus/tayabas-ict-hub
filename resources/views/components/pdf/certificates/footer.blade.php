@props([
    'footerTitle' => 'Certificate',
    'footerCode' => 'HRC',
])

<div class="footer-div">
    <div class="ref-line" style="margin-top: 50px; text-align: left;">
        HRMO / {{ $footerTitle }} &nbsp;
    </div>
    <div class="ref-line" style="margin-top: 0px; margin-bottom: 0; text-align: left;">
        {{ $footerCode }}-{{ now()->format('m-d-Y') }}
    </div>
    <div style="border-top: 2px solid #000; margin: 8px 0;"></div>
    <div style="text-align: center;">
        <img class="footer-img" src="{{ public_path('storage/pdffooter-logo.png') }}" alt="DepEd Footer">
    </div>
</div>

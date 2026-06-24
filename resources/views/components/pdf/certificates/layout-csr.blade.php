@props([
    'title' => 'Service Record',
    'subtitle' => '(To be accomplished by employer)',
])

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <x-pdf.certificates.styles-csr />
</head>

<body>
    {{-- HEADER --}}
    <x-pdf.certificates.header />

    {{-- TITLE SECTION --}}
    <div class="doc-title">{{ $title }}</div>
    <div class="doc-subtitle">{{ $subtitle }}</div>

    {{-- MAIN CONTENT --}}
    {{ $slot }}

    {{-- FOOTER --}}
    {{ $footer ?? '' }}
</body>

</html>

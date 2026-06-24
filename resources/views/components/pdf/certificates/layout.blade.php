@props([
    'title' => 'Certificate',
])

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <x-pdf.certificates.styles />
</head>

<body>
    <x-pdf.certificates.header />

    <div class="cert-title">{{ $title }}</div>

    {{ $slot }}

    {{ $footer ?? '' }}
</body>

</html>

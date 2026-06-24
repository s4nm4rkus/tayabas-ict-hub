@props([
    'title' => 'Form',
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <x-pdf.leave.styles-form />
    {{ $styles ?? '' }}
</head>

<body>
    {{ $slot }}
</body>

</html>

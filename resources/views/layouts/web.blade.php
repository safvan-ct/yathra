<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <meta name="title" content="Bus Timings | {{ config('app.name') }}">
    <meta name="description" content="Bus Timings">
    <meta name="keywords" content="Bus Timings">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Bus Timings">
    <meta property="og:description" content="Bus Timings">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Bus Timings">
    <meta property="twitter:description" content="Bus Timings">
    <meta property="twitter:image" content="{{ asset('img/logo.png') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('img/android-chrome-512x512.png') }}">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    @stack('styles')

    @php
        $seoData = [
            '@context' => 'https://schema.org',
            '@type' => 'Bus Timings',
            'name' => 'Yathra',
            'alternateName' => 'Bus Timings',
            'description' => 'Bus Timings',
            'logo' => asset('img/logo.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Kerala',
                'addressLocality' => 'Kerala',
                'addressRegion' => 'Kerala',
                'addressCountry' => 'IN',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
            ],
            'url' => url('/'),
            'telephone' => '+',
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '08:00',
                    'closes' => '20:00',
                ],
            ],
        ];
    @endphp

    <script type="application/ld-json">
        {!! json_encode($seoData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    @stack('scripts')
</body>

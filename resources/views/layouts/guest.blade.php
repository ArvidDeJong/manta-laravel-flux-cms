<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CMS' }}</title>
    <meta name="author" content="Darvis | Arvid de Jong | info@arvid.nl">
    <link rel="icon" type="image/png" href="/vendor/manta-cms/manta/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    {{ $slot }}
    <flux:toast position="top right" />

    @fluxScripts
    @stack('scripts')
</body>

</html>

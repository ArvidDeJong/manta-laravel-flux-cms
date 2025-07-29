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

    <link rel="stylesheet" href="{{ manta_cms_lib('/fontawesome-pro-6.7.2-web/css/all.css') }}">
    <link rel="stylesheet" href="{{ manta_cms_lib('/cropperjs/cropperjs.css') }}">
    <link rel="stylesheet" href="{{ manta_cms_lib('/flag-icons/css/flag-icons.min.css') }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
        integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/css/flag-icons.min.css">

    <script src="{{ manta_cms_lib('/cropperjs/cropperjs.js') }}"></script>
    <script src="{{ manta_cms_lib('/sortablejs/sortablejs.min.js') }}"></script>

    <script src="{{ manta_cms_lib('/tinymce-7.6.0/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ manta_cms_asset('/manta/js/passive-events-tinymce.js') }}"></script>

    <script>
        var tiny_css = '{{ env('TINY_CSS') }}'; //'/css/blaad.css';
    </script>

    <style>
        .tox-tinymce-aux {
            width: 100% !important;
        }

        [id]:target {
            background-color: yellow;
            /* Of een andere kleur naar keuze */
            transition: background-color 0.3s ease;
        }
    </style>
    @stack('styles')
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <x-manta.cms.header-flux-php />
    {{ $slot }}
    <flux:toast position="top right" />
    {{-- <footer class="sticky bottom-0 p-4 text-center bg-white text-slate-400 dark:bg-zinc-800">
        {{ date('Y') }} <a href="https://arvid.nl">ARVID.NL</a>
    </footer> --}}
    @fluxScripts
    @stack('scripts')
    <script src="{{ manta_cms_asset('/manta/js/cms.js') }}"></script>
    <script src="{{ env('SENTRY_REPLAY_URL') }}" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {});

        document.addEventListener('livewire:navigated', () => {});
    </script>
</body>

</html>

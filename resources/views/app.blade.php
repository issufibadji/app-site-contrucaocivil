@php
    use Illuminate\Support\Facades\Storage;

    $configs = \App\Models\AppConfig::all()->keyBy('key');
    $appName = $configs['app_name']->value ?? config('app.name', 'Justiça Federal');
    $iconPath = $configs['icon_app']->path_archive ?? null;
    $iconUrl = $iconPath ? Storage::url($iconPath) : asset('images/logo.png');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="icon" href="{{ $iconUrl }}" />
        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->
        <link href="https://fonts.googleapis.com/css2?family=Kode+Mono:wght@400;700&display=swap" rel="stylesheet" />
        <style>
            [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Kode Mono', monospace;
        }

        a {
            text-decoration: none;
        }

        .link-active {
            color: #000;
            font-weight: bold;
        }

        </style>
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="Kode Mono, monospace">
        @inertia
    </body>
</html>


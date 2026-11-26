@props(['title', 'description' => 'Asociación de piscicultores: pescado fresco de nuestros estanques, recetas y noticias del campo.', 'image' => null])
@php
    $links = [
        ['home', 'Inicio', '/'],
        ['site.products', 'Productos', 'productos'],
        ['site.recipes', 'Recetas', 'recetas*'],
        ['site.posts', 'Noticias', 'noticias*'],
    ];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_CO">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    @if ($image)
        <meta property="og:image" content="{{ url($image) }}">
    @endif
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-semibold text-water-800">
                <x-logo class="size-9" />
                <span>{{ config('app.name') }}</span>
            </a>
            <nav aria-label="Principal">
                <ul class="flex items-center gap-1 text-sm">
                    @foreach ($links as [$route, $label, $pattern])
                        <li class="hidden sm:block">
                            <a href="{{ route($route) }}" @class([
                                'rounded-md px-3 py-1.5',
                                'bg-water-50 font-semibold text-water-800' => request()->is($pattern),
                                'text-slate-600 hover:text-water-800' => ! request()->is($pattern),
                            ])>{{ $label }}</a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('login') }}" class="btn-primary ml-2 py-1.5">Ingresar</a></li>
                </ul>
            </nav>
        </div>
        <nav class="border-t border-slate-100 sm:hidden" aria-label="Secciones">
            <ul class="flex justify-around px-2 py-1 text-sm">
                @foreach ($links as [$route, $label, $pattern])
                    <li><a href="{{ route($route) }}" @class(['block px-2 py-1.5', 'font-semibold text-water-800' => request()->is($pattern), 'text-slate-600' => ! request()->is($pattern)])>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>
    </header>

    <main class="flex-1">{{ $slot }}</main>

    <footer class="bg-water-900 text-sm text-water-100">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 py-8 sm:grid-cols-2">
            <div>
                <p class="font-semibold text-white">{{ config('app.name') }}</p>
                <p class="mt-1">Asociación de piscicultores. Pescado de agua dulce criado con trazabilidad desde la siembra hasta la cosecha.</p>
            </div>
            <div class="sm:text-right">
                <p>¿Eres asociado? <a href="{{ route('login') }}" class="font-semibold text-white underline">Ingresa a tu bitácora</a></p>
                <p class="mt-1 text-water-200">&copy; {{ now()->year }} {{ config('app.name') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>

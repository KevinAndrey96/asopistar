<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 font-sans text-slate-900">
    <main class="card max-w-md p-8 text-center">
        <x-logo class="mx-auto size-12" />
        <p class="mt-4 text-sm font-semibold text-water-700">Error @yield('code')</p>
        <h1 class="mt-1 text-xl font-semibold">@yield('title')</h1>
        <p class="mt-2 text-sm text-slate-600">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn-primary mt-6">Volver al inicio</a>
    </main>
</body>
</html>

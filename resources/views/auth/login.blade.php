<x-layouts.site title="Ingresar · {{ config('app.name') }}">
    <x-slot:head><meta name="robots" content="noindex"></x-slot:head>
    <section class="bg-gradient-to-b from-water-50 to-white px-4 py-16">
        <div class="card mx-auto max-w-md p-8">
            <h1 class="text-xl font-semibold text-slate-900">Bitácora de la asociación</h1>
            <p class="mt-1 text-sm text-slate-600">Ingresa con la cuenta que te entregó la asociación.</p>

            <form method="post" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <x-field name="email" label="Correo" type="email" autocomplete="username" required autofocus />
                <x-field name="password" label="Contraseña" type="password" autocomplete="current-password" required />
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-water-600 focus:ring-water-500">
                    Mantener la sesión en este equipo
                </label>
                <button class="btn-primary w-full">Ingresar</button>
            </form>
            <p class="mt-6 text-xs text-slate-500">¿Olvidaste la contraseña? El administrador de la asociación puede asignarte una nueva.</p>
        </div>
    </section>
</x-layouts.site>

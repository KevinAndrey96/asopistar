<x-layouts.app title="Cambiar contraseña">
    <x-page-header title="Cambiar contraseña" subtitle="Usa al menos 10 caracteres con letras y números." />
    <form method="post" action="{{ route('password.update') }}" class="card max-w-lg space-y-4 p-6">
        @csrf
        @method('put')
        <x-field name="current_password" label="Contraseña actual" type="password" autocomplete="current-password" required />
        <x-field name="password" label="Nueva contraseña" type="password" autocomplete="new-password" required />
        <x-field name="password_confirmation" label="Confirma la nueva contraseña" type="password" autocomplete="new-password" required />
        <button class="btn-primary">Guardar contraseña</button>
    </form>
</x-layouts.app>

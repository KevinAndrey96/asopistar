@php $editing = $farmer->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$farmer->fullName() : 'Registrar piscicultor'">
    <x-page-header :title="$editing ? 'Editar '.$farmer->fullName() : 'Registrar piscicultor'"
        :back="$editing ? [route('admin.farmers.show', $farmer), $farmer->fullName()] : [route('admin.farmers.index'), 'Piscicultores']" />

    <form method="post" action="{{ $editing ? route('admin.farmers.update', $farmer) : route('admin.farmers.store') }}" class="card max-w-3xl space-y-6 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <fieldset class="grid gap-4 sm:grid-cols-2">
            <legend class="mb-2 text-sm font-semibold text-slate-900">Datos personales</legend>
            <x-field name="name" label="Nombres" :value="$farmer->name" maxlength="80" required />
            <x-field name="last_name" label="Apellidos" :value="$farmer->last_name" maxlength="80" required />
            <x-field name="document" label="Cédula" :value="$farmer->document" inputmode="numeric" required />
            <x-field name="gender" label="Género" type="select">
                <option value="">Sin dato</option>
                @foreach ($genders as $gender)
                    <option value="{{ $gender->value }}" @selected(old('gender', $farmer->gender?->value) === $gender->value)>{{ $gender->label() }}</option>
                @endforeach
            </x-field>
            <x-field name="phone" label="Teléfono" :value="$farmer->phone" type="tel" />
            <x-field name="member_code" label="Código de asociado" :value="$farmer->member_code" maxlength="20" />
        </fieldset>
        <fieldset class="grid gap-4 sm:grid-cols-2">
            <legend class="mb-2 text-sm font-semibold text-slate-900">Ubicación</legend>
            <x-field name="estate" label="Predio" :value="$farmer->estate" maxlength="120" />
            <x-field name="vereda" label="Vereda" :value="$farmer->vereda" maxlength="120" />
        </fieldset>
        <fieldset class="grid gap-4 sm:grid-cols-2">
            <legend class="mb-2 text-sm font-semibold text-slate-900">Acceso</legend>
            <x-field name="email" label="Correo" :value="$farmer->email" type="email" class="sm:col-span-2" required />
            <x-field name="password" :label="$editing ? 'Nueva contraseña (opcional)' : 'Contraseña inicial'" type="password" autocomplete="new-password" :required="! $editing" hint="Mínimo 10 caracteres con letras y números." />
            <x-field name="password_confirmation" label="Confirma la contraseña" type="password" autocomplete="new-password" :required="! $editing" />
            <input type="hidden" name="active" value="0">
            <label class="flex items-center gap-2 text-sm text-slate-700 sm:col-span-2">
                <input type="checkbox" name="active" value="1" @checked(old('active', $farmer->active)) class="rounded border-slate-300 text-water-600 focus:ring-water-500">
                Cuenta activa (puede ingresar a la bitácora)
            </label>
        </fieldset>
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Registrar piscicultor' }}</button>
            <a href="{{ $editing ? route('admin.farmers.show', $farmer) : route('admin.farmers.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>

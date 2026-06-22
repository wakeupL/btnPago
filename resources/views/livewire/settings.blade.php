<div class="max-w-xl mx-auto space-y-6">

    {{-- ── Sección: Logo ─────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 space-y-6">

        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Logo de la aplicación</h2>

        <div class="flex items-center gap-6">
            <img src="{{ appLogo() }}" alt="Logo actual" class="h-16 object-contain">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                @if ($tieneLogoPersonalizado)
                    <span class="text-green-600 dark:text-green-400 font-medium">Logo personalizado activo</span>
                @else
                    <span>Logo por defecto</span>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif
        @error('logo')
            <div class="text-sm text-red-600 bg-red-50 px-4 py-2 rounded">{{ $message }}</div>
        @enderror

        <form wire:submit="subirLogo" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Subir nuevo logo
                </label>
                <input type="file" wire:model="logo" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300">
                <p class="mt-1 text-xs text-gray-400">PNG, JPG, SVG o WebP — máximo 2 MB</p>
            </div>

            @if ($logo)
                <div class="flex items-center gap-3">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-12 object-contain rounded border border-gray-200 dark:border-gray-600">
                    <span class="text-xs text-gray-500">Vista previa</span>
                </div>
            @endif

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="subirLogo">Guardar logo</span>
                <span wire:loading wire:target="subirLogo">Subiendo...</span>
            </button>
        </form>

        @if ($tieneLogoPersonalizado)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <button wire:click="eliminarLogo"
                    wire:confirm="¿Eliminar el logo personalizado y volver al por defecto?"
                    class="text-sm text-red-500 hover:text-red-700 transition">
                    Eliminar logo personalizado
                </button>
            </div>
        @endif

    </div>

    {{-- ── Sección: Notificaciones ────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 space-y-6">

        <div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Notificaciones por correo</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                A esta dirección llegarán los avisos de cada pago confirmado.
            </p>
        </div>

        @if (session('success_correo'))
            <div class="text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded">
                {{ session('success_correo') }}
            </div>
        @endif

        <form wire:submit="guardarCorreo" class="space-y-4">
            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Correo responsable
                </label>
                <input type="email" id="correo" wire:model="correoNotificaciones"
                    placeholder="ejemplo@empresa.cl"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                @error('correoNotificaciones')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition disabled:opacity-50"
                wire:loading.attr="disabled" wire:target="guardarCorreo">
                <span wire:loading.remove wire:target="guardarCorreo">Guardar correo</span>
                <span wire:loading wire:target="guardarCorreo">Guardando...</span>
            </button>
        </form>

    </div>

</div>

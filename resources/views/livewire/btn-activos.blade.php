<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
        @if (session()->has('message'))
            <div class="p-3 text-green-700 bg-green-200 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>

    {{-- Buscador --}}
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
        <input
            wire:model.live.debounce.400ms="search"
            type="text"
            placeholder="Buscar por N° documento..."
            class="block w-full sm:w-72 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        />
    </div>

    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Responsable</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Documento</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Monto</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Fecha Ingreso</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Últ. Actualización</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Enlace</th>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
            @forelse ($activos as $activo)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $activo->user->name ?? '—' }}</td>
                    <th class="px-6 py-4">
                        <div class="font-medium text-gray-700"># {{ $activo->documento }}</div>
                    </th>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                            {{ chilePesos($activo->monto) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-600">
                            {{ $activo->created_at->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                            @if ($activo->created_at->eq($activo->updated_at))
                                Sin actualización
                            @else
                                {{ $activo->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <input type="text"
                            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            id="enlace{{ $activo->id }}" value="{{ $activo->url_corta }}" readonly />
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-4">
                            <button onclick="copiarAlPortapapeles('enlace{{ $activo->id }}')"
                                class="text-xs text-indigo-600 hover:underline font-medium">Copiar</button>
                            <button type="button" wire:click="eliminar({{ $activo->id }})"
                                title="Eliminar">
                                <img src="{{ asset('imgs/icons8-eliminar-48.png') }}" class="w-5 h-5">
                            </button>
                            <button type="button" wire:click="regenerarBtn({{ $activo->id }})"
                                title="Re-generar">
                                <img src="{{ asset('imgs/icons8-actualizar.gif') }}" class="w-5 h-5">
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">No hay botones activos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">
        {{ $activos->links('pagination::tailwind') }}
    </div>
</div>

<script>
    function copiarAlPortapapeles(idDelInput) {
        var input = document.getElementById(idDelInput);
        input.select();
        document.execCommand('copy');
    }
</script>

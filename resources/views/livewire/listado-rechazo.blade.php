<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
        @if (session()->has('message'))
            <div class="p-3 text-red-700 bg-red-100 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>

    {{-- Buscador --}}
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
        <input
            wire:model.debounce.400ms="search"
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
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Comprobante</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
            @forelse ($btnPagos as $pago)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $pago->user->name ?? '—' }}</td>
                    <th class="px-6 py-4">
                        <div class="font-medium text-gray-700"># {{ $pago->documento }}</div>
                    </th>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600">
                            {{ chilePesos($pago->monto) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-600">
                            {{ $pago->created_at->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-500">
                            @if ($pago->created_at->eq($pago->updated_at))
                                Sin actualización
                            @else
                                {{ $pago->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if ($pago->confirmacion)
                            <form method="POST" target="_blank" action="{{ route('descargar') }}">
                                @csrf
                                <input type="hidden" name="documento" value="{{ $pago->documento }}">
                                <button type="submit" title="Descargar comprobante PDF">
                                    <img src="{{ asset('imgs/icons8-pdf-30.png') }}" class="w-7 h-7 mx-auto" />
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic">Sin comprobante</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay transacciones rechazadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">
        {{ $btnPagos->links('pagination::tailwind') }}
    </div>
</div>

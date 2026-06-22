<div class="p-6 space-y-6">

    {{-- Selector de periodo --}}
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-700">Resumen</h2>
        <select wire:model.live="periodo"
            class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="7">Últimos 7 días</option>
            <option value="30" selected>Últimos 30 días</option>
            <option value="90">Últimos 90 días</option>
            <option value="365">Último año</option>
        </select>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Activos --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="rounded-full bg-indigo-50 p-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Botones Activos</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $totalActivos }}</p>
            </div>
        </div>

        {{-- Pagados en el periodo --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="rounded-full bg-green-50 p-3">
                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pagados ({{ $dias }}d)</p>
                <p class="text-2xl font-bold text-green-600">{{ $pagadosPeriodo }}</p>
                <p class="text-xs text-gray-400">Total histórico: {{ $totalPagados }}</p>
            </div>
        </div>

        {{-- Monto recaudado --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="rounded-full bg-yellow-50 p-3">
                <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Recaudado ({{ $dias }}d)</p>
                <p class="text-2xl font-bold text-yellow-600">{{ chilePesos($montoRecaudado) }}</p>
            </div>
        </div>

    </div>

    {{-- Generar nuevo botón --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Generar Botón de Pago</h3>
            <p class="text-xs text-gray-400 mt-1">Ingrese los datos para crear un nuevo botón de pago Transbank.</p>
        </div>
        <div class="p-5">
            <livewire:counter />
        </div>
    </div>

    {{-- Últimos pagos --}}
    @if ($ultimosPagos->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Últimos 5 pagos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                    <tr>
                        <th class="px-5 py-3">Documento</th>
                        <th class="px-5 py-3">Monto</th>
                        <th class="px-5 py-3">Tipo</th>
                        <th class="px-5 py-3">Autorización</th>
                        <th class="px-5 py-3">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($ultimosPagos as $pago)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800"># {{ $pago->orderPayment }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                {{ chilePesos($pago->amountPayment) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs">{{ $pago->typePayment }}</td>
                        <td class="px-5 py-3 font-mono text-xs">{{ $pago->authorizationCode }}</td>
                        <td class="px-5 py-3 text-xs">
                            {{ \Carbon\Carbon::parse($pago->transactionDatePayment)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Actividad diaria --}}
    @if ($actividadDiaria->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Actividad diaria — últimos {{ $dias }} días</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                    <tr>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Pagos</th>
                        <th class="px-5 py-3">Monto total</th>
                        <th class="px-5 py-3 w-48">Progreso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php $maxMonto = $actividadDiaria->max('monto') ?: 1; @endphp
                    @foreach ($actividadDiaria as $dia)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($dia->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                {{ $dia->total }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-700">
                            {{ chilePesos($dia->monto) }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-400 h-2 rounded-full"
                                    style="width: {{ round(($dia->monto / $maxMonto) * 100) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<x-guest-layout>
    <div class="w-full">

        {{-- Cabecera de éxito --}}
        <div class="bg-green-500 rounded-t-lg px-6 py-5 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-full mb-3">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-white text-2xl font-bold tracking-tight">¡Pago realizado con éxito!</h2>
            <p class="text-green-100 text-sm mt-1">Tu transacción fue procesada correctamente por WebpayPlus</p>
        </div>

        {{-- Monto destacado --}}
        <div class="bg-green-50 dark:bg-green-900/30 border-b border-green-100 dark:border-green-800 px-6 py-5 text-center">
            <p class="text-xs text-green-700 dark:text-green-400 font-semibold uppercase tracking-widest mb-1">Monto pagado</p>
            <p class="text-4xl font-extrabold text-green-700 dark:text-green-400">{{ chilePesos($buscarComprobante->amountPayment) }}</p>
        </div>

        {{-- Tabla de detalles --}}
        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium w-1/2">Estado</td>
                        <td class="py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                {{ $buscarComprobante->responseCode }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium">Código de autorización</td>
                        <td class="py-4 text-gray-900 dark:text-gray-100 font-mono font-semibold">{{ $buscarComprobante->authorizationCode }}</td>
                    </tr>

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium">Tipo de pago</td>
                        <td class="py-4 text-gray-900 dark:text-gray-100">{{ $buscarComprobante->typePayment }}</td>
                    </tr>

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium">Tarjeta</td>
                        <td class="py-4 text-gray-900 dark:text-gray-100 font-mono">•••• •••• •••• {{ $buscarComprobante->cardNumberPayment }}</td>
                    </tr>

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium">N° Documento</td>
                        <td class="py-4 text-gray-900 dark:text-gray-100 font-semibold">{{ $buscarComprobante->orderPayment }}</td>
                    </tr>

                    <tr>
                        <td class="py-4 pr-4 text-gray-500 dark:text-gray-400 font-medium">Fecha y hora</td>
                        <td class="py-4 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($buscarComprobante->updated_at)->format('d/m/Y H:i') }} hrs
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Botón descarga PDF --}}
        <div class="px-6 pb-6">
            <form method="POST" target="_blank" action="{{ route('descargar') }}">
                @csrf
                <input type="hidden" name="documento" value="{{ $buscarComprobante->orderPayment }}">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-800 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                    <img src="{{ asset('imgs/icons8-pdf-30.png') }}" class="w-5 h-5" alt="PDF">
                    Descargar comprobante PDF
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>

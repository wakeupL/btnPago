<x-guest-layout>

    <div class="max-w-7xl">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
                @if (session()->has('message'))
                    <div class="p-3 text-green-700 bg-green-200 rounded text-center">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <div class="p-6 bg-white">
                <h2
                    class="mb-4 text-center text-2xl font-extrabold tracking-dark leading-none text-gray-900 md:text-5xl lg:text-4xl dark:text-dark">
                    ¡Pago realizado con éxito!</h2>
                <div class="container mx-auto mt-2">
                    <table class="flex-auto">
                        @foreach ($buscarComprobante as $pago)
                            @php
                                //dd($buscarComprobante)
                            @endphp
                        @endforeach
                        <tr>
                            <th class="border">Estado de la operación</th>
                            <td class="text-center border">{{ $buscarComprobante->responseCode }}</td>
                        </tr>
                        <tr>
                            <th class="border">Código de autorización</th>
                            <td class="text-center border">{{ $buscarComprobante->authorizationCode }}
                            </td>
                        </tr>
                        <tr>
                            <th class="border">Tipo de pago</th>
                            <td class="text-center border">{{ $buscarComprobante->typePayment }}</td>
                        </tr>
                        <tr>
                            <th class="border">Últimos dígitos de la tarjeta</th>
                            <td class="text-center border">...
                                {{ $buscarComprobante->cardNumberPayment }}</td>
                        </tr>
                        <tr>
                            <th class="border">Documento interno</th>
                            <td class="text-center border">{{ $buscarComprobante->orderPayment }}</td>
                        </tr>
                        <tr>
                            <th class="border">Monto pagado</th>
                            <td class="text-center border">
                               
                                @php
                function chilePesos($value)
                {
                    return '$ ' . number_format($value, 0);
                }
                setlocale(LC_MONETARY, 'es_CL');
                echo chilePesos($buscarComprobante->amountPayment) . "\n";
            @endphp
                            </td>
                        </tr>
                        <tr>
                            <th class="border">Fecha y Hora</th>
                            <td class="text-center border">{{ $buscarComprobante->updated_at }}</td>
                        </tr>
                        <tr>
                            <th class="border">Descargar comprobante</th>
                            <td class=" text-center border">

                                <form method="POST" target="_blank" action="{{ route('descargar') }}">
                                    @csrf
                                    <input type="hidden" name="documento"
                                        value="{{ $buscarComprobante->orderPayment }}">
                                    <button type="submit"><img src="{{ asset('imgs/icons8-pdf-30.png') }}" /></button>
                                </form>
                            </td>
                        </tr>
                    </table>

                    <p class="text-center text-gray-500 text-xs pt-6 mt-6">
                        &copy;2023 Desarrollado por Ángel Oyarzún.
                    </p>
                </div>
            </div>

        </div>

    </div>
    </div>
</x-guest-layout>

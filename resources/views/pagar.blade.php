@if (empty($_GET['m'] && $_GET['d'] && $_GET['t'] && $_GET['u']))
    @php
        $nuevaURL = 'https://www.cbm.cl';
        header('Location: ' . $nuevaURL);
        die();
    @endphp
@else
    @php
        $monto = $_GET['m'];
        $documento = $_GET['d'];
        $token_ws = $_GET['t'];
        $url = $_GET['u'];
        $estado = $_GET['e'];

    @endphp
@endif
<x-guest-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
                @if (session()->has('message'))
                    <div class="p-3 text-green-700 bg-green-200 rounded text-center">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <div class="p-6 bg-white border-b border-gray-200">
                <h1
                    class="mb-4 text-center text-2xl font-extrabold tracking-dark leading-none text-gray-900 md:text-5xl lg:text-4xl dark:text-dark">
                    Botón de Pago</h1>
                <div class="w-full max-w-xs">

                    <form method="POST" action="@php echo $url.'?token_ws='.$token_ws; @endphp"
                        class=" bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <input type="hidden" name="token_ws" value="@php echo $token_ws; @endphp">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="documento">
                                Número de Documento
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                name="documento" id="documento" type="text" value="@php echo $documento; @endphp"
                                disabled>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="monto">
                                Monto
                            </label>
                            <input disabled
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                type="number" value="@php echo $monto; @endphp">

                        </div>
                        <div class="flex items-center justify-between">
                            @if ($estado != '1')
                                <button
                                    class="bg-blue-500 disabled:opacity-75 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit" disabled>
                                    Pagar
                                </button>
                                <p class="text-center"></p>
                            @else
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit">
                                    Pagar
                                </button>
                            @endif
                        </div>
                    </form>
                    <p class="text-center text-gray-500 text-xs">
                        &copy;2023 Desarrollado por Ángel Oyarzún. <br><b>Sistema se encuentra en fase beta.</b>
                    </p>
                </div>
            </div>

        </div>
        @switch($estado)
            @case('0')
                <!-- Alerta Boton pagado -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                        </svg>
                        <p>El botón ya ha sido pagado con éxito.</p>
                    </div>
                </div>
                <!-- Alerta Boton pagado -->
            @break

            @case('1')
                <!-- Problemas con el pago -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                    <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                        </svg>
                        <p>¿Problemas con el botón?, actualiza&nbsp;</p>
                        <form action="{{ route('actualizarToken') }}" method="post">
                            @csrf
                            <input type="hidden" name="documento" value="@php echo $documento; @endphp">
                            <input type="hidden" name="monto" value="@php echo $monto; @endphp">
                            <button type="submit" class="">AQUÍ</button>
                        </form>
                    </div>
                </div>
                <!-- Problemas con el pago -->
            @break

            @case('2')
                <!-- Alerta Boton desabilitado -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                    <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                        </svg>
                        <p>Botón desabilitado, favor contactar al soporte.</p>
                    </div>
                </div>
                <!-- Alerta Boton desabilitado -->
            @break

            @default
        @endswitch
    </div>
    </div>
</x-guest-layout>

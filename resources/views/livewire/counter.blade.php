<div class="p-6 bg-white border-b border-gray-200">
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
        @if (session()->has('message'))
            <div class="p-3 text-green-700 bg-green-200 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <br>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Generar Botón de Pago</h3>
                    <p class="mt-1 text-sm text-gray-600">Introduzca los datos para generar botón de pago.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form wire:submit.prevent="generarBtn" method="POST">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="documento" class="block text-sm font-medium text-gray-700">Número de
                                        Documento</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">Guía
                                            o Factura</span>
                                        <input wire:model="documento" type="number" required="" name="documento"
                                            placeholder="123456" id="documento"
                                            class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Se comenta para agregar más campos si necesitan más datos
            <div class="col-span-6 sm:col-span-4">
            <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
            <input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            -->
                                <div class="col-span-6 sm:col-span-6 lg:col-span-3">
                                    <label for="monto" class="block text-sm font-medium text-gray-700">Monto</label>
                                    <input wire:model="monto" type="text" required="" name="monto"
                                        id="monto" placeholder="$12.345"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                            <button type="submit"
                                class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Generar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>
</div>

@php
    function chilePesos($value)
    {
        return '$' . number_format($value, 0);
    }

    setlocale(LC_MONETARY, 'es_CL');
@endphp
<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
        @if (session()->has('message'))
            <div class="p-3 text-green-700 bg-green-200 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-8 py-6 font-medium text-gray-900">Responsable</th>
                <th scope="col" class="px-8 py-6 font-medium text-gray-900">Documento</th>
                <th scope="col" class="px-8 py-6 font-medium text-gray-900">Monto</th>
                <th scope="col" class="font-medium text-gray-900">Fecha Ingreso</th>
                <th scope="col" class=" font-medium text-gray-900">Últ. Actualización</th>
                <th scope="col" class="px-8 py-6 font-medium text-gray-900">Enlace</th>
                <th scope="col" class="px-8 py-6 font-medium text-gray-900">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
            @foreach ($activos as $activo)
                @if ($activo->estado == 1)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6">
                            @foreach ($users as $user)
                                @if ($user->id == $activo->user_id)
                                    {{ $user->name }}
                                @endif
                            @endforeach
                        </td>
                        <th class="px-8 py-6">
                            <div class="text-sm">
                                <div class="font-medium text-gray-700"># {{ $activo->documento }}</div>
                            </div>
                        </th>
                        <td class="px-8 py-6">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                {{ chilePesos($activo->monto) }}
                            </span>
                        </td>
                        <td class="">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-600">
                                {{ $activo->created_at }}
                            </span>
                        </td>
                        <td class="px-2">
                            <div class="flex gap-2">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                                    @if ($activo->created_at == $activo->updated_at)
                                        Sin actualización
                                    @else
                                        {{ $activo->updated_at }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <input type="text"
                                class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                id="enlace{{ $activo->id }}" value="{{ $activo->url_corta }}" />
                        </td>
                        <td class="px-8 py-6 ">
                            <div class="flex justify-center gap-4">

                                <button onclick="copiarAlPortapapeles('enlace{{ $activo->id }}')">Copiar</button>

                                <button type="button" wire:click='eliminar({{ $activo->id }})'><img
                                        src="{{ asset('imgs/icons8-eliminar-48.png') }}"></a></button>
                                <button type="button" wire:click='regenerarBtn({{ $activo->id }})'><img
                                        src="{{ asset('imgs/icons8-actualizar.gif') }}"></button>

                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    {{ $activos->links('pagination::tailwind') }}
</div>
<script>
    function copiarAlPortapapeles(idDelInput) {
        var input = document.getElementById(idDelInput);
        input.select();
        document.execCommand("copy");
    }
</script>

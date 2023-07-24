@php
    function chilePesos($value)
                {
                    return '$ ' . number_format($value, 0);
                }

                setlocale(LC_MONETARY, 'es_CL');
@endphp
<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
    <!-- alerta -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)">
        @if (session()->has('message'))
            <div class="p-3 text-green-700 bg-green-200 rounded">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <!-- alerta -->
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
            @foreach ($btnPagos as $pagos)
                @if ($pagos->estado == 0)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @foreach ($users as $user)
                                @if ($user->id == $pagos->user_id)
                                    {{ $user->name }}
                                @endif
                            @endforeach
                        </td>
                        <th class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-700"># {{ $pagos->documento }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">

                                {{ chilePesos($pagos->monto) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-600">
                                {{ $pagos->created_at }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                                    @if ($pagos->created_at == $pagos->updated_at)
                                        Sin actualización
                                    @else
                                        {{ $pagos->updated_at }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 text-center py-4">
                            <form method="POST" target="_blank" action="{{route('descargar')}}">
                                @csrf
                                <input type="hidden" name="documento" value="{{$pagos->documento}}">
                                <button type="submit"><img src="{{ asset('imgs/icons8-pdf-30.png') }}" /></button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    {{ $btnPagos->links('pagination::tailwind') }}
</div>
<!-- component -->

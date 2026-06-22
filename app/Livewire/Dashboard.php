<?php

namespace App\Livewire;

use App\Models\BotonPago;
use App\Models\ConfirmacionPagos;
use Livewire\Component;

class Dashboard extends Component
{
    public string $periodo = '30';

    public function render()
    {
        $dias  = (int) $this->periodo;
        $desde = now()->subDays($dias)->startOfDay();

        $totalActivos    = BotonPago::activos()->count();
        $totalPagados    = BotonPago::pagados()->count();

        $montoRecaudado = ConfirmacionPagos::where('created_at', '>=', $desde)
            ->sum('amountPayment');

        $pagadosPeriodo = BotonPago::pagados()
            ->where('updated_at', '>=', $desde)
            ->count();

        $ultimosPagos = ConfirmacionPagos::with('botonPago.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $actividadDiaria = ConfirmacionPagos::selectRaw(
            "date(created_at) as fecha, count(*) as total, sum(amountPayment) as monto"
        )
            ->where('created_at', '>=', $desde)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('livewire.dashboard', compact(
            'totalActivos',
            'totalPagados',
            'montoRecaudado',
            'pagadosPeriodo',
            'ultimosPagos',
            'actividadDiaria',
            'dias',
        ));
    }
}

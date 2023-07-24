<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BotonPago;
use App\Models\User;

class ListadoPagado extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.listado-pagado',[
            'btnPagos' => BotonPago::orderBy('updated_at', 'asc')->paginate(10),
            'users' => User::get(),
        ]);
    }
}

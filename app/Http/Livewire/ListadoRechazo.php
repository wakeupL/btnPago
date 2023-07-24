<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BotonPago;
use App\Models\User;

class ListadoRechazo extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.listado-rechazo',[
            'btnPagos' => BotonPago::paginate(10),
            'users' => User::get(),
        ]);
    }
}

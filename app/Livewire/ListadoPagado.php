<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BotonPago;

class ListadoPagado extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $btnPagos = BotonPago::pagados()
            ->with('user')
            ->when($this->search, fn($q) => $q->where('documento', 'like', '%' . $this->search . '%'))
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.listado-pagado', compact('btnPagos'));
    }
}

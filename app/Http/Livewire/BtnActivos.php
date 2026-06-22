<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BotonPago;
use Transbank\Webpay\WebpayPlus\Transaction;

class BtnActivos extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function eliminar(int $id): void
    {
        BotonPago::destroy($id);
        session()->flash('message', 'Botón eliminado correctamente.');
    }

    public function regenerarBtn(int $id): void
    {
        $datos = BotonPago::findOrFail($id);
        $url = route('respuestaPago');

        $response = (new Transaction)->create($datos->documento, $datos->documento, $datos->monto, $url);

        $corta_token = BotonPago::generarCortaToken();
        $url_corta   = route('urlCorta') . '?e=' . $corta_token;

        $datos->token_ws    = $response->getToken();
        $datos->url_corta   = $url_corta;
        $datos->corta_token = $corta_token;
        $datos->save();

        session()->flash('message', 'Botón de pago re-generado correctamente.');
    }

    public function render()
    {
        $activos = BotonPago::activos()
            ->with('user')
            ->when($this->search, fn($q) => $q->where('documento', 'like', '%' . $this->search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.btn-activos', compact('activos'));
    }
}

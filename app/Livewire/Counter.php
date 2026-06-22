<?php

namespace App\Livewire;

use Livewire\Component;
use Transbank\Webpay\WebpayPlus\Transaction;
use App\Models\BotonPago;
use Livewire\WithPagination;

class Counter extends Component
{
    use WithPagination;

    public $monto, $documento, $respuesta, $url, $token_ws;

    public function generarBtn(): void
    {
        $url = route('respuestaPago');

        $response = (new Transaction)->create($this->documento, $this->documento, $this->monto, $url);

        $corta_token = BotonPago::generarCortaToken();
        $url_corta   = route('urlCorta') . '?e=' . $corta_token;

        $nuevoBtn = new BotonPago();
        $nuevoBtn->user_id     = auth()->user()->id;
        $nuevoBtn->documento   = $this->documento;
        $nuevoBtn->monto       = $this->monto;
        $nuevoBtn->token_ws    = $response->getToken();
        $nuevoBtn->url_wp      = $response->getUrl();
        $nuevoBtn->url_corta   = $url_corta;
        $nuevoBtn->corta_token = $corta_token;
        $nuevoBtn->estado      = BotonPago::ESTADO_ACTIVO;
        $nuevoBtn->save();

        session()->flash('message', 'Se ha generado nuevo botón de pago.');
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

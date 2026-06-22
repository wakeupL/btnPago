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
        $this->validate([
            'documento' => 'required|integer',
            'monto'     => 'required|integer|min:1',
        ]);

        // El documento no puede repetirse: garantiza que el comprobante y el estado
        // del botón siempre correspondan a un único pago.
        $existente = BotonPago::where('documento', $this->documento)->first();

        if ($existente && $existente->estado === BotonPago::ESTADO_PAGADO) {
            $this->addError('documento', "El documento {$this->documento} ya fue pagado.");
            return;
        }

        if ($existente && $existente->estado === BotonPago::ESTADO_ACTIVO) {
            $this->addError('documento', "Ya existe un botón de pago activo para el documento {$this->documento}.");
            return;
        }

        $url      = route('respuestaPago');
        $response = (new Transaction)->create($this->documento, $this->documento, $this->monto, $url);

        // Si el botón existía pero estaba rechazado, se reutiliza la misma fila
        // (reintento) en vez de crear un duplicado.
        $boton = $existente ?? new BotonPago();
        $boton->user_id   = auth()->user()->id;
        $boton->documento = $this->documento;
        $boton->monto     = $this->monto;
        $boton->token_ws  = $response->getToken();
        $boton->url_wp    = $response->getUrl();

        if (! $boton->exists) {
            $corta_token       = BotonPago::generarCortaToken();
            $boton->corta_token = $corta_token;
            $boton->url_corta   = route('urlCorta') . '?e=' . $corta_token;
        }

        $boton->estado = BotonPago::ESTADO_ACTIVO;
        $boton->save();

        session()->flash('message', 'Se ha generado nuevo botón de pago.');
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

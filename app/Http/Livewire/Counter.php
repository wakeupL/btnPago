<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Transbank\Webpay\WebpayPlus\Transaction;
use App\Models\BotonPago;
use Livewire\WithPagination;

class Counter extends Component
{
    use WithPagination;


    public $monto, $documento, $respuesta,$url,$token_ws;

    public function generarBtn(){

        $url = route('respuestaPago');


        $response = (new Transaction)->create($this->documento, $this->documento, $this->monto, $url);

        $url_corta = substr($response->getToken(), 0 , 6);
        $corta_token = $url_corta;
        // urlCorta = http://tuenlace.com/url/ || e =  enlace + 6 inciciales del token
        $url_corta = route('urlCorta')."?e=".$url_corta;

        $nuevoBtn = new BotonPago();
        $nuevoBtn->user_id = auth()->user()->id;
        $nuevoBtn->documento = $this->documento;
        $nuevoBtn->monto = $this->monto;
        $nuevoBtn->token_ws = $response->getToken();
        $nuevoBtn->url_wp = $response->getUrl();
        $nuevoBtn->url_corta = $url_corta;
        $nuevoBtn->corta_token = $corta_token;
        $nuevoBtn->estado = 1;
        $nuevoBtn->save();
        session()->flash('message', 'Se ha generado nuevo botón de pago.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BotonPago;
use App\Models\User;
use Transbank\Webpay\WebpayPlus\Transaction;

class BtnActivos extends Component
{
    use WithPagination;


    private $pagination = 10;

    public function eliminar($id){
        BotonPago::destroy($id);
        return redirect()->route('btn-activos');
    }

    public function regenerarBtn($id){

        $datos = BotonPago::find($id);
        $documento  = $datos->documento;
        $monto      = $datos->monto;
        $url = route('respuestaPago');

        $response = (new Transaction)->create($documento, $documento, $monto, $url);

        $url_corta = substr($response->getToken(), 0 , 6);
        $corta_token = $url_corta;
        // urlCorta = http://tuenlace.com/url/ || e =  enlace + 6 inciciales del token
        $url_corta = route('urlCorta')."?e=".$url_corta;

        $datos->token_ws = $response->getToken();
        $datos->url_corta = $url_corta;
        $datos->corta_token = $corta_token;
        $datos->save();
        session()->flash('message', 'Se ha re-generado botón de pago.');
        return redirect()->route('btn-activos');
    }

    public function render()
    {
        return view('livewire.btn-activos', [
            'activos' => BotonPago::orderBy('created_at', 'desc')->paginate($this->pagination),
            'users' => User::get(),
        ]);
    }
}

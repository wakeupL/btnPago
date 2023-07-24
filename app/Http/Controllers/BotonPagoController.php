<?php

namespace App\Http\Controllers;

use App\Models\BotonPago;
use App\Models\ConfirmacionPagos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Transbank\Webpay\WebpayPlus\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


class BotonPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BotonPago  $botonPago
     * @return \Illuminate\Http\Response
     */
    public function show(BotonPago $botonPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BotonPago  $botonPago
     * @return \Illuminate\Http\Response
     */
    public function edit(BotonPago $botonPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BotonPago  $botonPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BotonPago $botonPago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BotonPago  $botonPago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function respuestaPago(Request $request){

        if(!empty($request->input('TBK_TOKEN')&&$request->input('TBK_ORDEN_COMPRA')&&$request->input('TBK_ID_SESION'))){

            session()->flash('message', 'Ups... Problemas en la transacción, consulte a su banco.');
            return redirect()->route('error');
            }

        date_default_timezone_set('America/Santiago');
        //Guardamos el token en variable
        $token = $request->token_ws;

        //Consultamos a la lib. de transbank el estados del token
        //Obteniendo los datos que contienen el mismo
        $response = (new Transaction)->commit($token);

        //Verificamos si la transaccion es aprobada o rechazada
        if ($response->isApproved()) {
            // Transacción Aprobada

            //Guardamos todas las respuestas en variables para guardarlos en la BDD
            $autenticacion = $response->getVci();

            switch ($autenticacion) {
                case 'TSY':
                    $autenticacion = 'Autenticación Exitosa';
                    break;
                case 'TSN':
                    $autenticacion = 'Autenticación Rechazada';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'NP':
                    $autenticacion = 'No Participa, sin autenticacion';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'U3':
                    $autenticacion = 'Falla conexion, Autenticacion Rechazada';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'INV':
                    $autenticacion = 'Datos Invalidos';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'A':
                    $autenticacion = 'Intento';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'CNP1':
                    $autenticacion = 'Comercio no participa';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'EOP':
                    $autenticacion = 'Error operacional';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'BNA':
                    $autenticacion = 'BIN no adherido';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;
                case 'ENA':
                    $autenticacion = 'Emisor no adherido';

                    session()->flash('message', 'Ups...'.$autenticacion);
                    return redirect()->route('error');
                    break;


                default:
                    $autenticacion = 'CODIGO DE AUTORIZACION';
                    break;
            }

            $codigoRespuesta = $response->getResponseCode();

            switch ($codigoRespuesta) {
                case '0':
                    $codigoRespuesta = 'Transacción aprobada';
                    break;
                case '-1':
                    $codigoRespuesta = 'Rechazo - Posible error en el ingreso de datos de la transaccion. No se guardara informacion.';

                    session()->flash('message', 'Ups...'.$codigoRespuesta. '. Contacte al soporte.');
                    return redirect()->route('error');
                    break;
                case '-2':
                    $codigoRespuesta = 'Rechazo - Se produjo fallo al procesar la transaccion, este mensaje de rechazo se encuentra relacionado a parámetros de la tarjeta y/o su cuenta asociada. No se guardara informacion.';

                    session()->flash('message', 'Ups...'.$codigoRespuesta. '. Contacte al soporte.');
                    return redirect()->route('error');
                    break;
                case '-3':
                    $codigoRespuesta = 'Rechazo - Error en Transaccion. No se guardará informacion.';

                    session()->flash('message', 'Ups...'.$codigoRespuesta. '. Contacte al soporte.');
                    return redirect()->route('error');
                    break;
                case '-4':
                    $codigoRespuesta = 'Rechazo - Rechazada por parte del emisor. No se guardara informacion.';

                    session()->flash('message', 'Ups...'.$codigoRespuesta. '. Contacte al soporte.');
                    return redirect()->route('error');
                    break;
                case '-5':
                    $codigoRespuesta = 'Rechazo - Transaccion con riesgo de posible fraude. No se guardara informacion.';

                    session()->flash('message', 'Ups...'.$codigoRespuesta. '. Contacte al soporte.');
                    return redirect()->route('error');
                    break;
                default:
                    # code...
                    break;
            }
            //dd($response);
            $monto = $response->getAmount();
            $estadoTransaccion = $response->getStatus();
            $ordenCompra = $response->getBuyOrder();
            $sesionId = $response->getSessionId();
            foreach($response->getCardDetail() as $card){$ultimosDigitosTarjeta = $card;}
            $fechaContable = $response->getAccountingDate();
            $fechaTransaccion = $response->getTransactionDate();
            $codigoAutorizacion = $response->getAuthorizationCode();
            $codigoTipoPago = $response->getPaymentTypeCode();
            $importeCuotas = $response->getInstallmentsAmount();
            $numeroCuotas = $response->getInstallmentsNumber();
            $balance = $response->getBalance();
            switch($codigoTipoPago){
                case('VD'):
                    $codigoTipoPago = 'Venta Debito';
                    break;
                case('VN'):
                    $codigoTipoPago = 'Venta Normal';
                    break;
                case('VC'):
                    $codigoTipoPago = 'Venta en Cuotas';
                    break;
                case('SI'):
                    $codigoTipoPago = '3 cuotas sin interes';
                    break;
                case('S2'):
                    $codigoTipoPago = '2 cuotas sin interes';
                    break;
                case('NC'):
                    $codigoTipoPago = $numeroCuotas.' Cuotas de $'.number_format($importeCuotas).' sin interes';
                    break;
                case('VP'):
                    $codigoTipoPago = 'Venta Prepago';
                    break;
                default:
                    $codigoTipoPago = 'Ups, ha pasado algo...';
                    break;

            }

            //Guardamos las respuestas en la preparecion de insercion a la BDD
            $pagoRealizado = new ConfirmacionPagos();
            $pagoRealizado->authPayment = $autenticacion;
            $pagoRealizado->responseCode = $codigoRespuesta;
            $pagoRealizado->statusPayment = $estadoTransaccion;
            $pagoRealizado->amountPayment = $monto;
            $pagoRealizado->authorizationCode = $codigoAutorizacion;
            $pagoRealizado->typePayment = $codigoTipoPago;
            $pagoRealizado->accountingDate = $fechaContable;
            $pagoRealizado->sessionIdPayment = $sesionId;
            $pagoRealizado->orderPayment = $ordenCompra;
            $pagoRealizado->cardNumberPayment = $ultimosDigitosTarjeta;
            $pagoRealizado->transactionDatePayment = $fechaTransaccion;
            $pagoRealizado->installmentsAmount = $importeCuotas;
            $pagoRealizado->installmentsNumber = $numeroCuotas;
            $pagoRealizado->balance = $balance;

            //dd($pagoRealizado);

            //Guardamos los datos en la BDD
            $pagoRealizado->save();

            //Cambiamos el estado del boton
            $estado = BotonPago::where('documento', '=', $ordenCompra)->first();
            $estado->estado = '0';
            $estado->save();

            //Mandamos correo para que revisen si el pago fué exitoso
            $email_to = "email@email.com";
            $email_subject = "Se ha realizado un BOTON DE PAGO - ". $ordenCompra;

            //Cuerpo del mensaje
            $email_message = "Detalles del boton de pago:\n\n";
            $email_message .= "Monto: " . $monto . "\n";
            $email_message .= "Documento: " . $ordenCompra . "\n";

            // Ahora se envía el e-mail usando la función mail() de PHP
            // Estando en el servidor se le tiene que eliminar el @ de la funcion MAIL
            $headers = 'From: '.$email_to."\r\n".
            'Reply-To: '.$email_to."\r\n" .
            'X-Mailer: PHP/' . phpversion();
            @mail($email_to, $email_subject, $email_message, $headers);


            //buscamos y mandamos los datos a la vista para presentar comprobante
            $buscarComprobante = ConfirmacionPagos::where('orderPayment', '=', $ordenCompra)->first();
            //dd($buscarComprobante);
            return view('comprobantePago')->with('buscarComprobante', $buscarComprobante);

            //return redirect()->route('dashboard');

        } else {
         // Transacción rechazada
         //echo "bai";
         //$response = (new Transaction)->commit($token);
         //dd($response);
         session()->flash('message', 'Ups... Problemas en la transacción, consulte a su banco.');
         return redirect()->route('error');
        }

    }

    public function actualizarToken(Request $request){

        $documento = $request->documento;
        $monto = $request->monto;
        $datos = BotonPago::where('documento', '=', $documento)
                           ->where('monto', '=', $monto)
                           ->first();
        $url = route('respuestaPago');
        $response = (new Transaction)->create($documento, $documento, $monto, $url);
        $datos->token_ws = $response->getToken();
        $datos->save();
        session()->flash('message', 'Se ha actualizado correctamente');
        return redirect()->route('pagar','d='.$documento.'&m='.$monto.'&t='.$datos->token_ws.'&u='.$datos->url_wp.'&e='.$datos->estado);
    }

    public function error(){
        return view('error');
    }

    public function urlCorta(Request $request){

        //recibimos el token recortado
        $enlace = $request->e;
        //Consultamos a la BDD el token recortado con el registro
        $url = DB::table('boton_pagos')->where('corta_token', '=', $enlace)->first();
        //asignamos los resultados del token recortado con el boton de pago
        $documento = $url->documento;
        $monto = $url->monto;
        $token_ws = $url->token_ws;
        $url_wp = $url->url_wp;
        $estado = $url->estado;
        //redireccionamos a la ruta de pago + las variables de datos que la vista necesita para realizar el formulario de transbank
        return redirect()->route('pagar','d='.$documento.'&m='.$monto.'&t='.$token_ws.'&u='.$url_wp.'&e='.$estado);
        //dd($url);
        //return redirect($enlace);
    }

    public function descargarComprobante(Request $request){

        $documento = $request->documento;

        $buscarComprobante = ConfirmacionPagos::where('orderPayment', '=', $documento)->first();
        //dd($buscarComprobante);
        $pdf = Pdf::loadView('descargarPdf', compact('buscarComprobante'));
        return $pdf->stream();
    }
}

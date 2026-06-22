<?php

namespace App\Http\Controllers;

use App\Mail\PagoRealizado;
use App\Models\BotonPago;
use App\Models\ConfirmacionPagos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Transbank\Webpay\WebpayPlus\Transaction;

class BotonPagoController extends Controller
{
    private const VCI_LABELS = [
        'TSY' => 'Autenticación Exitosa',
        'TSN' => 'Autenticación Rechazada',
        'NP'  => 'No Participa, sin autenticación',
        'U3'  => 'Falla conexión, Autenticación Rechazada',
        'INV' => 'Datos Inválidos',
        'A'   => 'Intento de autenticación',
        'CNP1'=> 'Comercio no participa',
        'EOP' => 'Error operacional',
        'BNA' => 'BIN no adherido',
        'ENA' => 'Emisor no adherido',
    ];

    private const RESPONSE_CODES = [
        '0'  => 'Transacción aprobada',
        '-1' => 'Rechazo - Posible error en el ingreso de datos de la transacción.',
        '-2' => 'Rechazo - Fallo al procesar la transacción.',
        '-3' => 'Rechazo - Error en Transacción.',
        '-4' => 'Rechazo - Rechazada por el emisor.',
        '-5' => 'Rechazo - Transacción con riesgo de posible fraude.',
    ];

    private const PAYMENT_TYPES = [
        'VD'  => 'Venta Débito',
        'VN'  => 'Venta Normal',
        'VC'  => 'Venta en Cuotas',
        'SI'  => '3 cuotas sin interés',
        'S2'  => '2 cuotas sin interés',
        'VP'  => 'Venta Prepago',
    ];

    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(BotonPago $botonPago) {}
    public function edit(BotonPago $botonPago) {}
    public function update(Request $request, BotonPago $botonPago) {}

    public function destroy($id)
    {
        BotonPago::destroy($id);
    }

    public function respuestaPago(Request $request)
    {
        // Pago cancelado por el usuario desde Transbank
        if ($request->has('TBK_TOKEN')) {
            $this->marcarRechazado($request->input('TBK_ORDEN_COMPRA'));
            session()->flash('message', 'Pago cancelado. Puede intentarlo nuevamente o consultar a su banco.');
            return redirect()->route('error');
        }

        date_default_timezone_set('America/Santiago');

        $token    = $request->token_ws;
        $response = (new Transaction)->commit($token);

        if (!$response->isApproved()) {
            $this->marcarRechazado($response->getBuyOrder());
            session()->flash('message', 'Ups... Problemas en la transacción, consulte a su banco.');
            return redirect()->route('error');
        }

        $vci = $response->getVci();
        if ($vci !== 'TSY') {
            $label = self::VCI_LABELS[$vci] ?? 'Error de autenticación';
            $this->marcarRechazado($response->getBuyOrder());
            session()->flash('message', 'Ups... ' . $label);
            return redirect()->route('error');
        }

        $responseCode = (string) $response->getResponseCode();
        if ($responseCode !== '0') {
            $label = self::RESPONSE_CODES[$responseCode] ?? 'Error desconocido';
            $this->marcarRechazado($response->getBuyOrder());
            session()->flash('message', 'Ups... ' . $label . ' Contacte al soporte.');
            return redirect()->route('error');
        }

        $paymentTypeCode = $response->getPaymentTypeCode();
        $numeroCuotas    = $response->getInstallmentsNumber();
        $importeCuotas   = $response->getInstallmentsAmount();

        if ($paymentTypeCode === 'NC') {
            $tipoLabel = $numeroCuotas . ' Cuotas de ' . chilePesos($importeCuotas) . ' sin interés';
        } else {
            $tipoLabel = self::PAYMENT_TYPES[$paymentTypeCode] ?? 'Tipo desconocido';
        }

        $cardDetail = $response->getCardDetail();
        $ultimosDigitos = is_array($cardDetail) ? array_values($cardDetail)[0] ?? '' : '';

        $pagoRealizado = ConfirmacionPagos::create([
            'authPayment'            => self::VCI_LABELS[$vci] ?? $vci,
            'responseCode'           => self::RESPONSE_CODES[$responseCode] ?? $responseCode,
            'statusPayment'          => $response->getStatus(),
            'amountPayment'          => $response->getAmount(),
            'authorizationCode'      => $response->getAuthorizationCode(),
            'typePayment'            => $tipoLabel,
            'accountingDate'         => $response->getAccountingDate(),
            'sessionIdPayment'       => $response->getSessionId(),
            'orderPayment'           => $response->getBuyOrder(),
            'cardNumberPayment'      => $ultimosDigitos,
            'transactionDatePayment' => $response->getTransactionDate(),
            'installmentsAmount'     => $importeCuotas,
            'installmentsNumber'     => $numeroCuotas,
            'balance'                => $response->getBalance(),
        ]);

        BotonPago::where('documento', $response->getBuyOrder())
            ->update(['estado' => BotonPago::ESTADO_PAGADO]);

        try {
            Mail::to(config('mail.from.address'))->send(new PagoRealizado($pagoRealizado));
        } catch (\Throwable $e) {
            logger()->error('Error enviando email de pago: ' . $e->getMessage());
        }

        $buscarComprobante = ConfirmacionPagos::where('orderPayment', $response->getBuyOrder())->first();
        return view('comprobantePago', compact('buscarComprobante'));
    }

    public function actualizarToken(Request $request)
    {
        $documento = $request->documento;
        $monto     = $request->monto;

        $datos = BotonPago::where('documento', $documento)->where('monto', $monto)->firstOrFail();
        $url   = route('respuestaPago');

        $response = (new Transaction)->create($documento, $documento, $monto, $url);
        $datos->token_ws = $response->getToken();
        $datos->save();

        session()->flash('message', 'Se ha actualizado correctamente');
        return redirect()->route('pagar', 'd=' . $documento . '&m=' . $monto . '&t=' . $datos->token_ws . '&u=' . $datos->url_wp . '&e=' . $datos->estado);
    }

    public function error()
    {
        return view('error');
    }

    public function urlCorta(Request $request)
    {
        $url = DB::table('boton_pagos')->where('corta_token', $request->e)->firstOrFail();
        return redirect()->route('pagar', 'd=' . $url->documento . '&m=' . $url->monto . '&t=' . $url->token_ws . '&u=' . $url->url_wp . '&e=' . $url->estado);
    }

    public function descargarComprobante(Request $request)
    {
        $buscarComprobante = ConfirmacionPagos::where('orderPayment', $request->documento)->first();

        if (! $buscarComprobante) {
            abort(404, 'No existe comprobante para este pago. Los pagos rechazados o anulados sin confirmación no generan comprobante.');
        }

        $pdf = Pdf::loadView('descargarPdf', compact('buscarComprobante'));
        return $pdf->stream();
    }

    private function marcarRechazado(?string $documento): void
    {
        if ($documento) {
            BotonPago::where('documento', $documento)
                ->update(['estado' => BotonPago::ESTADO_RECHAZADO]);
        }
    }
}

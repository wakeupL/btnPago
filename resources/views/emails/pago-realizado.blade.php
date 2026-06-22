<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pago Confirmado</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <!-- Card container -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color:#1e3a5f;border-radius:8px 8px 0 0;padding:32px 40px;">
                            <img src="{{ appLogo() }}" alt="{{ config('app.name') }}" width="160" style="display:block;max-width:160px;height:auto;">
                        </td>
                    </tr>

                    <!-- Success banner -->
                    <tr>
                        <td align="center" style="background-color:#16a34a;padding:20px 40px;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-right:10px;vertical-align:middle;">
                                        <!-- Checkmark icon -->
                                        <div style="width:32px;height:32px;background-color:#ffffff;border-radius:50%;display:inline-block;text-align:center;line-height:32px;">
                                            <span style="color:#16a34a;font-size:18px;font-weight:bold;">✓</span>
                                        </div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <span style="color:#ffffff;font-size:20px;font-weight:bold;letter-spacing:0.5px;">Pago Confirmado</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="background-color:#ffffff;padding:36px 40px;">

                            <p style="margin:0 0 8px 0;color:#374151;font-size:15px;">Estimado/a,</p>
                            <p style="margin:0 0 28px 0;color:#374151;font-size:15px;line-height:1.6;">
                                Se ha procesado exitosamente un pago a través de <strong>WebpayPlus</strong>. A continuación el detalle de la transacción:
                            </p>

                            <!-- Monto destacado -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                                <tr>
                                    <td align="center" style="background-color:#f0fdf4;border:2px solid #bbf7d0;border-radius:8px;padding:20px;">
                                        <p style="margin:0 0 4px 0;color:#15803d;font-size:13px;text-transform:uppercase;letter-spacing:1px;font-weight:bold;">Monto pagado</p>
                                        <p style="margin:0;color:#15803d;font-size:32px;font-weight:bold;">{{ chilePesos($confirmacion->amountPayment) }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Detalle de transacción -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-bottom:28px;">

                                <tr style="background-color:#f9fafb;">
                                    <td colspan="2" style="padding:12px 20px;border-bottom:1px solid #e5e7eb;">
                                        <span style="color:#6b7280;font-size:12px;text-transform:uppercase;letter-spacing:1px;font-weight:bold;">Detalle de la transacción</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#6b7280;font-size:13px;width:45%;">N° Documento</td>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#111827;font-size:14px;font-weight:600;">{{ $confirmacion->orderPayment }}</td>
                                </tr>

                                <tr style="background-color:#fafafa;">
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#6b7280;font-size:13px;">Código de autorización</td>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#111827;font-size:14px;font-weight:600;font-family:monospace;">{{ $confirmacion->authorizationCode }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#6b7280;font-size:13px;">Tipo de pago</td>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#111827;font-size:14px;">{{ $confirmacion->typePayment }}</td>
                                </tr>

                                <tr style="background-color:#fafafa;">
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#6b7280;font-size:13px;">Tarjeta</td>
                                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;color:#111827;font-size:14px;font-family:monospace;">•••• •••• •••• {{ $confirmacion->cardNumberPayment }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:14px 20px;color:#6b7280;font-size:13px;">Fecha y hora</td>
                                    <td style="padding:14px 20px;color:#111827;font-size:14px;">{{ \Carbon\Carbon::parse($confirmacion->transactionDatePayment)->format('d/m/Y H:i') }}</td>
                                </tr>

                            </table>

                            <!-- Nota informativa -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                                <tr>
                                    <td style="background-color:#eff6ff;border-left:4px solid #3b82f6;border-radius:0 4px 4px 0;padding:14px 18px;">
                                        <p style="margin:0;color:#1e40af;font-size:13px;line-height:1.5;">
                                            Este correo es una confirmación automática del pago. Guárdelo como comprobante de su transacción.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f9fafb;border-top:1px solid #e5e7eb;border-radius:0 0 8px 8px;padding:24px 40px;text-align:center;">
                            <p style="margin:0 0 6px 0;color:#374151;font-size:14px;font-weight:600;">{{ config('app.name') }}</p>
                            <p style="margin:0;color:#9ca3af;font-size:12px;">
                                Pago procesado de forma segura a través de
                                <span style="color:#1e3a5f;font-weight:bold;">WebpayPlus · Transbank</span>
                            </p>
                            <p style="margin:12px 0 0 0;color:#d1d5db;font-size:11px;">
                                Desarrollado con ♥ por
                                <a href="https://wakedev.cl" style="color:#6b7280;text-decoration:none;">wakedev.cl</a>
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- / Card container -->

            </td>
        </tr>
    </table>
    <!-- / Wrapper -->

</body>
</html>

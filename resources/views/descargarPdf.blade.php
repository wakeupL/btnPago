<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CBM | Comprobante de pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        .table {
            text-align: center;
            align-items: center;
            justify-items: center;
            padding: 0 0 1em 0;

        }
        table {
            margin: 0 auto;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .logo {
            padding: 15px;
            border-bottom: 1px solid #9d9d9d;
            text-align: center;
        }
        .titulo {
            text-align: center;
            font-family:'Times New Roman', Times, serif;
            font-size: 30px;
            text-shadow: 2px 2px #000;
            padding-top: 10px;
            border-bottom: 1px solid #9d9d9d;
            padding-bottom: 20px;
        }
        .container {
            border: 1px solid #000;
            margin-top: 10%;
        }
        .img-footer {
            border-top: 1px solid #9d9d9d;
            padding-top: 20px;
            border-bottom: 0px ;
        }
        .sin-border {
            border: 0px;
        }
        .footer {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo text-center">
            @php $logoPrincipal = pdfImage(appLogoPath()); @endphp
            @if ($logoPrincipal)
                <img src="{{ $logoPrincipal }}" alt="Logo" width="175">
            @endif
        </div>
        <div class="titulo">
            Comprobante de Pago <br>
            {{ chilePesos($buscarComprobante->amountPayment) }}
        </div>
        <div class="table">
            <table>
                <tr>
                    <td class="">Estado de la operación</td>
                    <td class="text-right">{{ $buscarComprobante->responseCode }}</td>
                </tr>
                <tr>
                    <td class="">Código de autorización</td>
                    <td class="text-right">{{ $buscarComprobante->authorizationCode }}
                    </td>
                </tr>
                <tr>
                    <td class="">Tipo de pago</td>
                    <td class="text-right">{{ $buscarComprobante->typePayment }}</td>
                </tr>
                <tr>
                    <td class="">Últimos dígitos de la tarjeta</td>
                    <td class="text-right">...
                        {{ $buscarComprobante->cardNumberPayment }}</td>
                </tr>
                <tr>
                    <td class="">Documento interno</td>
                    <td class="text-right">{{ $buscarComprobante->orderPayment }}</td>
                </tr>
                <tr>
                    <td class="">Fecha y Hora</td>
                    <td class="text-right">{{ $buscarComprobante->updated_at }}</td>
                </tr>
                @php $logoFooter = pdfImage(public_path('imgs/comprobante-footer.png')); @endphp
                @if ($logoFooter)
                <tr>
                    <td class="img-footer text-center" style="padding-top:50px;" colspan="2">
                        <img src="{{ $logoFooter }}" alt="" width="100">
                    </td>
                </tr>
                @endif
                <tr>
                    <td colspan="2" class="sin-border text-center footer">
                        <p>
                            Casa Matriz : Cuarta Avenida #1130, San Miguel, Santiago.<br>
                            Sucursal Zona Sur: Maipú #1071, Concecpción.<br>
                            +56 2 2553 3499 <br>
                            cobranza@cbm.cl<br>
                            www.cbm.cl
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>
</html>

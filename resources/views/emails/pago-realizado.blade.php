<x-mail::message>
# Nuevo Botón de Pago Realizado

Se ha procesado un pago exitoso con los siguientes datos:

| Campo              | Detalle |
|--------------------|---------|
| **Documento**      | {{ $confirmacion->orderPayment }} |
| **Monto**          | {{ chilePesos($confirmacion->amountPayment) }} |
| **Tipo de Pago**   | {{ $confirmacion->typePayment }} |
| **Autorización**   | {{ $confirmacion->authorizationCode }} |
| **Tarjeta**        | **** **** **** {{ $confirmacion->cardNumberPayment }} |
| **Fecha**          | {{ $confirmacion->transactionDatePayment }} |

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>

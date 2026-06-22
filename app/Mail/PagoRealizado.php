<?php

namespace App\Mail;

use App\Models\ConfirmacionPagos;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoRealizado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ConfirmacionPagos $confirmacion) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Botón de Pago realizado - ' . $this->confirmacion->orderPayment,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pago-realizado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

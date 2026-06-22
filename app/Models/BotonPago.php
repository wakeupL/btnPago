<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BotonPago extends Model
{
    use HasFactory;

    protected $table = 'boton_pagos';

    const ESTADO_ACTIVO   = 1;
    const ESTADO_PAGADO   = 0;
    const ESTADO_RECHAZADO = 2;

    protected $fillable = [
        'user_id',
        'documento',
        'monto',
        'token_ws',
        'url_wp',
        'url_corta',
        'corta_token',
        'estado',
    ];

    protected $casts = [
        'monto'    => 'integer',
        'documento' => 'integer',
        'estado'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmacion()
    {
        return $this->hasOne(ConfirmacionPagos::class, 'orderPayment', 'documento');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    public function scopePagados($query)
    {
        return $query->where('estado', self::ESTADO_PAGADO);
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
    }

    /**
     * Genera un corta_token de 8 caracteres alfanuméricos (a-z0-9) único en la tabla.
     * Reintenta hasta 10 veces ante colisión (probabilidad ≈ 0 con 36^8 combinaciones).
     */
    public static function generarCortaToken(): string
    {
        $intentos = 0;
        do {
            $token = strtolower(Str::random(8));
            $intentos++;
        } while (
            self::where('corta_token', $token)->exists() && $intentos < 10
        );

        return $token;
    }
}

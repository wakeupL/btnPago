<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmacionPagos extends Model
{
    use HasFactory;

    protected $table = 'confirmaciones';

    protected $fillable = [
        'authPayment',
        'responseCode',
        'statusPayment',
        'amountPayment',
        'authorizationCode',
        'typePayment',
        'accountingDate',
        'sessionIdPayment',
        'orderPayment',
        'cardNumberPayment',
        'transactionDatePayment',
        'installmentsAmount',
        'installmentsNumber',
        'balance',
    ];

    public function botonPago()
    {
        return $this->belongsTo(BotonPago::class, 'orderPayment', 'documento');
    }
}

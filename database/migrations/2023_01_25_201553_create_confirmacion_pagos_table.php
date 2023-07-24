<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmacion_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('authPayment');
            $table->string('responseCode');
            $table->string('statusPayment');
            $table->string('amountPayment');
            $table->string('authorizationCode')->nullable();
            $table->string('typePayment');
            $table->string('accountingDate');
            $table->string('sessionIdPayment');
            $table->string('orderPayment');
            $table->string('cardNumberPayment');
            $table->string('transactionDatePayment');
            $table->string('installmentsAmount')->nullable();
            $table->string('installmentsNumber')->nullable();
            $table->string('balance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('confirmacion_pagos');
    }
};

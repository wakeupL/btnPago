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
        Schema::create('boton_pagos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('documento');
            $table->integer('monto');
            $table->string('token_ws');
            $table->string('url_wp');
            $table->string('url_corta');
            $table->string('corta_token');
            $table->integer('estado');
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
        Schema::dropIfExists('boton_pagos');
    }
};

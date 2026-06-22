<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boton_pagos', function (Blueprint $table) {
            $table->unique('corta_token');
        });
    }

    public function down(): void
    {
        Schema::table('boton_pagos', function (Blueprint $table) {
            $table->dropUnique(['corta_token']);
        });
    }
};

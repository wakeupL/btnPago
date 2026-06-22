<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pre-chequeo: un índice único sobre datos con duplicados aborta con un error
        // SQL críptico. Mejor fallar con un mensaje claro indicando qué resolver.
        $duplicados = DB::table('boton_pagos')
            ->select('documento')
            ->groupBy('documento')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('documento');

        if ($duplicados->isNotEmpty()) {
            throw new \RuntimeException(
                'No se puede aplicar el índice único en boton_pagos.documento: existen ' .
                'documentos duplicados (' . $duplicados->implode(', ') . '). ' .
                'Resuélvelos antes de correr esta migración.'
            );
        }

        Schema::table('boton_pagos', function (Blueprint $table) {
            $table->unique('documento');
        });
    }

    public function down(): void
    {
        Schema::table('boton_pagos', function (Blueprint $table) {
            $table->dropUnique(['documento']);
        });
    }
};

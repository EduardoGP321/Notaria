<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes_folios', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_volumen_libro');
            $table->integer('folio_inicial');
            $table->integer('folio_final');
            $table->integer('folios_disponibles');
            $table->date('fecha_autorizacion');
            $table->timestamps();

            $table->index('numero_volumen_libro', 'idx_lotes_volumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes_folios');
    }
};

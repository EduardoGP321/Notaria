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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_persona', ['fisica', 'moral'])->default('fisica');
            $table->string('nombre_razon_social', 200);
            $table->string('rfc', 13)->nullable();
            $table->string('curp', 18)->nullable();
            $table->enum('estado_civil', [
                'soltero',
                'casado_sociedad_conyugal',
                'casado_separacion_bienes',
                'divorciado',
                'viudo'
            ])->nullable();
            $table->string('telefono', 20);
            $table->string('email', 120)->nullable();
            $table->text('domicilio_fiscal')->nullable();
            $table->timestamps();

            $table->index('nombre_razon_social', 'idx_clientes_nombre');
            $table->index('rfc', 'idx_clientes_rfc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

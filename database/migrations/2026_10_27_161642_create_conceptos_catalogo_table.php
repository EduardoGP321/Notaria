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
        Schema::create('conceptos_catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 30)->unique();
            $table->string('nombre', 150);
            $table->enum('categoria', [
                'impuesto',
                'derecho',
                'gasto_gestoria',
                'avaluo',
                'honorarios_notaria'
            ]);
            $table->string('dependencia_destino', 100)->nullable();
            $table->integer('dias_resolucion_est')->default(3);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptos_catalogo');
    }
};

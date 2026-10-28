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
        Schema::create('declaranot_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritura_id')->constrained('escrituras')->cascadeOnDelete();
            $table->foreignId('vendedor_cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->decimal('monto_isr_retenido', 12, 2)->default(0.00);
            $table->string('folio_declaranot_sat', 80)->nullable();
            $table->string('formato_pdf_url', 255)->nullable();
            $table->boolean('enviado_por_correo')->default(false);
            $table->dateTime('fecha_envio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declaranot_envios');
    }
};

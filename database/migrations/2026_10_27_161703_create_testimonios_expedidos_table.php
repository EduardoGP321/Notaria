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
        Schema::create('testimonios_expedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritura_id')->constrained('escrituras')->cascadeOnDelete();
            $table->enum('tipo_documento', [
                'primer_testimonio',
                'segundo_testimonio',
                'copia_certificada',
                'copia_simple'
            ]);
            $table->enum('destinatario_tipo', [
                'adquirente_cliente',
                'institucion_credito',
                'archivo_notaria',
                'juzgado'
            ]);
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->integer('numero_fojas');
            $table->date('fecha_expedicion');
            $table->date('fecha_entrega')->nullable();
            $table->string('persona_recibe_nombre', 150)->nullable();
            $table->foreignId('usuario_entrega_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('archivo_escaneado_url', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonios_expedidos');
    }
};

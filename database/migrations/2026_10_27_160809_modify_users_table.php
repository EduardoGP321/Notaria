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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono', 20)->nullable()->after('password');
            $table->string('iniciales', 10)->nullable()->after('telefono');
            $table->boolean('activo')->default(true)->after('iniciales');

            $table->index('activo', 'idx_users_activo');
        });

        Schema::create('puestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('clave', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('puesto_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('puesto_id')->constrained('puestos')->cascadeOnDelete();
            $table->date('fecha_asignacion')->useCurrent();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'puesto_id'], 'unq_user_puesto');
            $table->index(['user_id', 'es_principal'], 'idx_user_puesto_principal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puesto_user');
        Schema::dropIfExists('puestos');
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_activo');
            $table->dropColumn(['telefono', 'iniciales', 'activo']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'users',
        'puestos',
        'municipios',
        'instituciones_credito',
        'tipos_operacion',
        'conceptos_catalogo',
        'requisitos_catalogo',
        'peritos_valuadores',
        'clientes',
        'presupuestos',
        'presupuesto_detalles',
        'expedientes',
        'expediente_comparecientes',
        'expediente_documentos',
        'lotes_folios',
        'escrituras',
        'avisos_preventivos',
        'avaluos',
        'cortes_caja',
        'recibos_ingresos',
        'pagos_gestoria',
        'declaranot_envios',
        'citas_agenda',
        'inmuebles',
        'folios_incidencias',
        'testimonios_expedidos',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};

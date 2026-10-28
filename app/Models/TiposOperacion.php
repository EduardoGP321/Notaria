<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TiposOperacion
 *
 * @property int $id
 * @property string $nombre
 * @property bool $requiere_avaluo
 * @property bool $requiere_rpp
 * @property string|null $plantilla_word_oficio
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|Expediente[] $expedientes
 * @property Collection|OperacionRequisito[] $operacionRequisitos
 * @property Collection|Presupuesto[] $presupuestos
 */
class TiposOperacion extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_operacion';

    protected $casts = [
        'requiere_avaluo' => 'bool',
        'requiere_rpp' => 'bool',
        'activo' => 'bool',
    ];

    protected $fillable = [
        'nombre',
        'requiere_avaluo',
        'requiere_rpp',
        'plantilla_word_oficio',
        'activo',
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'tipo_operacion_id');
    }

    public function operacionRequisitos(): HasMany
    {
        return $this->hasMany(OperacionRequisito::class, 'tipo_operacion_id');
    }

    public function operacion_requisitos(): HasMany
    {
        return $this->operacionRequisitos();
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class, 'tipo_operacion_id');
    }
}
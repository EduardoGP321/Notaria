<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Municipio
 *
 * @property int $id
 * @property string $nombre
 * @property string $estado
 * @property string $porcentaje_isabi
 * @property int $dias_promedio_avaluo
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|Expediente[] $expedientes
 * @property Collection|Inmueble[] $inmuebles
 * @property Collection|Presupuesto[] $presupuestos
 */
class Municipio extends Model
{
    use SoftDeletes;

    protected $table = 'municipios';

    protected $casts = [
        'porcentaje_isabi' => 'decimal:2',
        'dias_promedio_avaluo' => 'int',
        'activo' => 'bool',
    ];

    protected $fillable = [
        'nombre',
        'estado',
        'porcentaje_isabi',
        'dias_promedio_avaluo',
        'activo',
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function inmuebles(): HasMany
    {
        return $this->hasMany(Inmueble::class);
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class);
    }
}
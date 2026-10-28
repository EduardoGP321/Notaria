<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InstitucionesCredito
 *
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property int|null $dias_limite_rpp
 * @property int $dias_alerta_previa
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|Expediente[] $expedientes
 */
class InstitucionesCredito extends Model
{
    use SoftDeletes;

    protected $table = 'instituciones_credito';

    protected $casts = [
        'dias_limite_rpp' => 'int',
        'dias_alerta_previa' => 'int',
        'activo' => 'bool',
    ];

    protected $fillable = [
        'nombre',
        'tipo',
        'dias_limite_rpp',
        'dias_alerta_previa',
        'activo',
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'institucion_credito_id');
    }
}
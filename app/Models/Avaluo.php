<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Avaluo
 *
 * @property int $id
 * @property int $expediente_id
 * @property int $perito_id
 * @property Carbon $fecha_solicitud
 * @property Carbon $fecha_estimada_entrega
 * @property Carbon|null $fecha_recepcion_real
 * @property string|null $valor_catastral
 * @property string|null $valor_comercial
 * @property string|null $archivo_avaluo_url
 * @property string $estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Expediente $expediente
 * @property PeritosValuador $perito
 */
class Avaluo extends Model
{
    use SoftDeletes;

    protected $table = 'avaluos';

    protected $casts = [
        'expediente_id' => 'int',
        'perito_id' => 'int',
        'fecha_solicitud' => 'date',
        'fecha_estimada_entrega' => 'date',
        'fecha_recepcion_real' => 'date',
        'valor_catastral' => 'decimal:2',
        'valor_comercial' => 'decimal:2',
    ];

    protected $fillable = [
        'expediente_id',
        'perito_id',
        'fecha_solicitud',
        'fecha_estimada_entrega',
        'fecha_recepcion_real',
        'valor_catastral',
        'valor_comercial',
        'archivo_avaluo_url',
        'estatus',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function perito(): BelongsTo
    {
        return $this->belongsTo(PeritosValuador::class, 'perito_id');
    }

    public function peritos_valuadore(): BelongsTo
    {
        return $this->perito();
    }
}
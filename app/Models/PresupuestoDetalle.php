<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresupuestoDetalle
 *
 * @property int $id
 * @property int $presupuesto_id
 * @property int $concepto_id
 * @property string $monto_estimado
 * @property string|null $observaciones
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property ConceptosCatalogo $concepto
 * @property Presupuesto $presupuesto
 */
class PresupuestoDetalle extends Model
{
    use SoftDeletes;

    protected $table = 'presupuesto_detalles';

    protected $casts = [
        'presupuesto_id' => 'int',
        'concepto_id' => 'int',
        'monto_estimado' => 'decimal:2',
    ];

    protected $fillable = [
        'presupuesto_id',
        'concepto_id',
        'monto_estimado',
        'observaciones',
    ];

    public function concepto(): BelongsTo
    {
        return $this->belongsTo(ConceptosCatalogo::class, 'concepto_id');
    }

    public function conceptos_catalogo(): BelongsTo
    {
        return $this->concepto();
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }
}
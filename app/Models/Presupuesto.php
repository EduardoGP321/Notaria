<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Presupuesto
 *
 * @property int $id
 * @property string $folio_presupuesto
 * @property int $cliente_id
 * @property int $abogado_id
 * @property int $tipo_operacion_id
 * @property int $municipio_id
 * @property string $valor_operacion
 * @property string $porcentaje_aplicado
 * @property string $subtotal_impuestos_der
 * @property string $honorarios_notariales
 * @property string $iva_honorarios
 * @property string $total_presupuesto
 * @property string $estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property User $abogado
 * @property Cliente $cliente
 * @property Municipio $municipio
 * @property TiposOperacion $tiposOperacion
 * @property Collection|Expediente[] $expedientes
 * @property Collection|PresupuestoDetalle[] $presupuestoDetalles
 */
class Presupuesto extends Model
{
    use SoftDeletes;

    protected $table = 'presupuestos';

    protected $casts = [
        'cliente_id' => 'int',
        'abogado_id' => 'int',
        'tipo_operacion_id' => 'int',
        'municipio_id' => 'int',
        'valor_operacion' => 'decimal:2',
        'porcentaje_aplicado' => 'decimal:2',
        'subtotal_impuestos_der' => 'decimal:2',
        'honorarios_notariales' => 'decimal:2',
        'iva_honorarios' => 'decimal:2',
        'total_presupuesto' => 'decimal:2',
    ];

    protected $fillable = [
        'folio_presupuesto',
        'cliente_id',
        'abogado_id',
        'tipo_operacion_id',
        'municipio_id',
        'valor_operacion',
        'porcentaje_aplicado',
        'subtotal_impuestos_der',
        'honorarios_notariales',
        'iva_honorarios',
        'total_presupuesto',
        'estatus',
    ];

    public function abogado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'abogado_id');
    }

    public function user(): BelongsTo
    {
        return $this->abogado();
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tiposOperacion(): BelongsTo
    {
        return $this->belongsTo(TiposOperacion::class, 'tipo_operacion_id');
    }

    public function tipos_operacion(): BelongsTo
    {
        return $this->tiposOperacion();
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function presupuestoDetalles(): HasMany
    {
        return $this->hasMany(PresupuestoDetalle::class);
    }

    public function presupuesto_detalles(): HasMany
    {
        return $this->presupuestoDetalles();
    }
}

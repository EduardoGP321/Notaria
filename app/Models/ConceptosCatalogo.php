<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ConceptosCatalogo
 *
 * @property int $id
 * @property string $clave
 * @property string $nombre
 * @property string $categoria
 * @property string|null $dependencia_destino
 * @property int $dias_resolucion_est
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|PagosGestoria[] $pagosGestoria
 * @property Collection|PresupuestoDetalle[] $presupuestoDetalles
 */
class ConceptosCatalogo extends Model
{
    use SoftDeletes;

    protected $table = 'conceptos_catalogo';

    protected $casts = [
        'dias_resolucion_est' => 'int',
        'activo' => 'bool',
    ];

    protected $fillable = [
        'clave',
        'nombre',
        'categoria',
        'dependencia_destino',
        'dias_resolucion_est',
        'activo',
    ];

    public function pagosGestoria(): HasMany
    {
        return $this->hasMany(PagosGestoria::class, 'concepto_id');
    }

    public function pagos_gestoria(): HasMany
    {
        return $this->pagosGestoria();
    }

    public function presupuestoDetalles(): HasMany
    {
        return $this->hasMany(PresupuestoDetalle::class, 'concepto_id');
    }

    public function presupuesto_detalles(): HasMany
    {
        return $this->presupuestoDetalles();
    }
}
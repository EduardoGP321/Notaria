<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CortesCaja
 *
 * @property int $id
 * @property string $tipo_corte
 * @property Carbon $fecha_inicio
 * @property Carbon $fecha_fin
 * @property string $total_efectivo
 * @property string $total_bancos
 * @property string $monto_total
 * @property int $usuario_elabora_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property User $usuarioElabora
 * @property Collection|RecibosIngreso[] $recibosIngresos
 */
class CortesCaja extends Model
{
    use SoftDeletes;

    protected $table = 'cortes_caja';

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'total_efectivo' => 'decimal:2',
        'total_bancos' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'usuario_elabora_id' => 'int',
    ];

    protected $fillable = [
        'tipo_corte',
        'fecha_inicio',
        'fecha_fin',
        'total_efectivo',
        'total_bancos',
        'monto_total',
        'usuario_elabora_id',
    ];

    public function usuarioElabora(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_elabora_id');
    }

    public function user(): BelongsTo
    {
        return $this->usuarioElabora();
    }

    public function recibosIngresos(): HasMany
    {
        return $this->hasMany(RecibosIngreso::class, 'corte_caja_id');
    }

    public function recibos_ingresos(): HasMany
    {
        return $this->recibosIngresos();
    }
}
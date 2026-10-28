<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RecibosIngreso
 *
 * @property int $id
 * @property string $folio_recibo_impreso
 * @property int $expediente_id
 * @property int $cliente_id
 * @property int $usuario_cobro_id
 * @property int|null $corte_caja_id
 * @property string $nombre_concepto_recibo
 * @property string $importe
 * @property string $forma_pago
 * @property string|null $banco_referencia
 * @property bool $impreso_en_foliado
 * @property bool $requiere_cfdi
 * @property string|null $uuid_factura_notaria
 * @property Carbon $fecha_pago
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Cliente $cliente
 * @property CortesCaja|null $corteCaja
 * @property Expediente $expediente
 * @property User $usuarioCobro
 */
class RecibosIngreso extends Model
{
    use SoftDeletes;

    protected $table = 'recibos_ingresos';

    protected $casts = [
        'expediente_id' => 'int',
        'cliente_id' => 'int',
        'usuario_cobro_id' => 'int',
        'corte_caja_id' => 'int',
        'importe' => 'decimal:2',
        'impreso_en_foliado' => 'bool',
        'requiere_cfdi' => 'bool',
        'fecha_pago' => 'datetime',
    ];

    protected $fillable = [
        'folio_recibo_impreso',
        'expediente_id',
        'cliente_id',
        'usuario_cobro_id',
        'corte_caja_id',
        'nombre_concepto_recibo',
        'importe',
        'forma_pago',
        'banco_referencia',
        'impreso_en_foliado',
        'requiere_cfdi',
        'uuid_factura_notaria',
        'fecha_pago',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function corteCaja(): BelongsTo
    {
        return $this->belongsTo(CortesCaja::class, 'corte_caja_id');
    }

    public function cortes_caja(): BelongsTo
    {
        return $this->corteCaja();
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function usuarioCobro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_cobro_id');
    }

    public function user(): BelongsTo
    {
        return $this->usuarioCobro();
    }
}
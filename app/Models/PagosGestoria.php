<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PagosGestoria
 *
 * @property int $id
 * @property int $expediente_id
 * @property int $concepto_id
 * @property string $linea_captura_referencia
 * @property string|null $banco_pago
 * @property string $monto_solicitado
 * @property int $admin_caja_id
 * @property int|null $gestor_id
 * @property int|null $secretaria_recibe_id
 * @property Carbon $fecha_solicitud_recurso
 * @property Carbon|null $fecha_entrega_gestor
 * @property Carbon|null $fecha_pago_dependencia
 * @property Carbon|null $fecha_factura_secretaria
 * @property string|null $comprobante_pago_url
 * @property string|null $factura_catastro_pdf_url
 * @property string $estatus_pago
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property User $adminCaja
 * @property User|null $gestor
 * @property User|null $secretariaRecibe
 * @property ConceptosCatalogo $concepto
 * @property Expediente $expediente
 */
class PagosGestoria extends Model
{
    use SoftDeletes;

    protected $table = 'pagos_gestoria';

    protected $casts = [
        'expediente_id' => 'int',
        'concepto_id' => 'int',
        'admin_caja_id' => 'int',
        'gestor_id' => 'int',
        'secretaria_recibe_id' => 'int',
        'monto_solicitado' => 'decimal:2',
        'fecha_solicitud_recurso' => 'date',
        'fecha_entrega_gestor' => 'date',
        'fecha_pago_dependencia' => 'date',
        'fecha_factura_secretaria' => 'date',
    ];

    protected $fillable = [
        'expediente_id',
        'concepto_id',
        'linea_captura_referencia',
        'banco_pago',
        'monto_solicitado',
        'admin_caja_id',
        'gestor_id',
        'secretaria_recibe_id',
        'fecha_solicitud_recurso',
        'fecha_entrega_gestor',
        'fecha_pago_dependencia',
        'fecha_factura_secretaria',
        'comprobante_pago_url',
        'factura_catastro_pdf_url',
        'estatus_pago',
    ];

    public function adminCaja(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_caja_id');
    }

    public function gestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function secretariaRecibe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretaria_recibe_id');
    }

    public function user(): BelongsTo
    {
        return $this->secretariaRecibe();
    }

    public function concepto(): BelongsTo
    {
        return $this->belongsTo(ConceptosCatalogo::class, 'concepto_id');
    }

    public function conceptos_catalogo(): BelongsTo
    {
        return $this->concepto();
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
}
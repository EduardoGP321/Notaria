<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DeclaranotEnvio
 *
 * @property int $id
 * @property int $escritura_id
 * @property int $vendedor_cliente_id
 * @property string $monto_isr_retenido
 * @property string|null $folio_declaranot_sat
 * @property string|null $formato_pdf_url
 * @property bool $enviado_por_correo
 * @property Carbon|null $fecha_envio
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Escritura $escritura
 * @property Cliente $vendedorCliente
 */
class DeclaranotEnvio extends Model
{
    use SoftDeletes;

    protected $table = 'declaranot_envios';

    protected $casts = [
        'escritura_id' => 'int',
        'vendedor_cliente_id' => 'int',
        'monto_isr_retenido' => 'decimal:2',
        'enviado_por_correo' => 'bool',
        'fecha_envio' => 'datetime',
    ];

    protected $fillable = [
        'escritura_id',
        'vendedor_cliente_id',
        'monto_isr_retenido',
        'folio_declaranot_sat',
        'formato_pdf_url',
        'enviado_por_correo',
        'fecha_envio',
    ];

    public function escritura(): BelongsTo
    {
        return $this->belongsTo(Escritura::class);
    }

    public function vendedorCliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'vendedor_cliente_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->vendedorCliente();
    }
}
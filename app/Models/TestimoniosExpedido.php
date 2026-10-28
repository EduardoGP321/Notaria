<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TestimoniosExpedido
 *
 * @property int $id
 * @property int $escritura_id
 * @property string $tipo_documento
 * @property string $destinatario_tipo
 * @property int|null $cliente_id
 * @property int $numero_fojas
 * @property Carbon $fecha_expedicion
 * @property Carbon|null $fecha_entrega
 * @property string|null $persona_recibe_nombre
 * @property int|null $usuario_entrega_id
 * @property string|null $archivo_escaneado_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Cliente|null $cliente
 * @property Escritura $escritura
 * @property User|null $usuarioEntrega
 */
class TestimoniosExpedido extends Model
{
    use SoftDeletes;

    protected $table = 'testimonios_expedidos';

    protected $casts = [
        'escritura_id' => 'int',
        'cliente_id' => 'int',
        'usuario_entrega_id' => 'int',
        'numero_fojas' => 'int',
        'fecha_expedicion' => 'date',
        'fecha_entrega' => 'date',
    ];

    protected $fillable = [
        'escritura_id',
        'tipo_documento',
        'destinatario_tipo',
        'cliente_id',
        'numero_fojas',
        'fecha_expedicion',
        'fecha_entrega',
        'persona_recibe_nombre',
        'usuario_entrega_id',
        'archivo_escaneado_url',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function escritura(): BelongsTo
    {
        return $this->belongsTo(Escritura::class);
    }

    public function usuarioEntrega(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_entrega_id');
    }

    public function user(): BelongsTo
    {
        return $this->usuarioEntrega();
    }
}
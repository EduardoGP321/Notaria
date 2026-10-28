<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExpedienteDocumento
 *
 * @property int $id
 * @property int $expediente_id
 * @property int $requisito_id
 * @property bool $entregado_fisico
 * @property string|null $archivo_digital_url
 * @property Carbon|null $fecha_recepcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Expediente $expediente
 * @property RequisitosCatalogo $requisito
 */
class ExpedienteDocumento extends Model
{
    use SoftDeletes;

    protected $table = 'expediente_documentos';

    protected $casts = [
        'expediente_id' => 'int',
        'requisito_id' => 'int',
        'entregado_fisico' => 'bool',
        'fecha_recepcion' => 'datetime',
    ];

    protected $fillable = [
        'expediente_id',
        'requisito_id',
        'entregado_fisico',
        'archivo_digital_url',
        'fecha_recepcion',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function requisito(): BelongsTo
    {
        return $this->belongsTo(RequisitosCatalogo::class, 'requisito_id');
    }

    public function requisitos_catalogo(): BelongsTo
    {
        return $this->requisito();
    }
}
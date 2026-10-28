<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OperacionRequisito
 *
 * @property int $id
 * @property int $tipo_operacion_id
 * @property int $requisito_id
 * @property bool $obligatorio
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property RequisitosCatalogo $requisito
 * @property TiposOperacion $tiposOperacion
 */
class OperacionRequisito extends Model
{
    protected $table = 'operacion_requisitos';

    protected $casts = [
        'tipo_operacion_id' => 'int',
        'requisito_id' => 'int',
        'obligatorio' => 'bool',
    ];

    protected $fillable = [
        'tipo_operacion_id',
        'requisito_id',
        'obligatorio',
    ];

    public function requisito(): BelongsTo
    {
        return $this->belongsTo(RequisitosCatalogo::class, 'requisito_id');
    }

    public function requisitos_catalogo(): BelongsTo
    {
        return $this->requisito();
    }

    public function tiposOperacion(): BelongsTo
    {
        return $this->belongsTo(TiposOperacion::class, 'tipo_operacion_id');
    }

    public function tipos_operacion(): BelongsTo
    {
        return $this->tiposOperacion();
    }
}

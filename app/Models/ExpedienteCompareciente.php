<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExpedienteCompareciente
 *
 * @property int $id
 * @property int $expediente_id
 * @property int $cliente_id
 * @property string $rol_compareciente
 * @property bool $recibe_factura_catastro
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Cliente $cliente
 * @property Expediente $expediente
 */
class ExpedienteCompareciente extends Model
{
    use SoftDeletes;

    protected $table = 'expediente_comparecientes';

    protected $casts = [
        'expediente_id' => 'int',
        'cliente_id' => 'int',
        'recibe_factura_catastro' => 'bool',
    ];

    protected $fillable = [
        'expediente_id',
        'cliente_id',
        'rol_compareciente',
        'recibe_factura_catastro',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
}
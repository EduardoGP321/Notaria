<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AvisosPreventivo
 *
 * @property int $id
 * @property int $expediente_id
 * @property string $tipo_aviso
 * @property Carbon $fecha_presentacion_rpp
 * @property Carbon $fecha_vencimiento
 * @property string|null $numero_entrada_rpp
 * @property string|null $documento_formato_url
 * @property string $estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Expediente $expediente
 */
class AvisosPreventivo extends Model
{
    use SoftDeletes;

    protected $table = 'avisos_preventivos';

    protected $casts = [
        'expediente_id' => 'int',
        'fecha_presentacion_rpp' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    protected $fillable = [
        'expediente_id',
        'tipo_aviso',
        'fecha_presentacion_rpp',
        'fecha_vencimiento',
        'numero_entrada_rpp',
        'documento_formato_url',
        'estatus',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
}
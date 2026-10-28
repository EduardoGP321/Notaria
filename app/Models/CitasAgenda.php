<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CitasAgenda
 *
 * @property int $id
 * @property int|null $expediente_id
 * @property int $abogado_id
 * @property string $nombre_interesado
 * @property string $tipo_cita
 * @property Carbon $fecha_hora_inicio
 * @property Carbon $fecha_hora_fin
 * @property string $estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property User $abogado
 * @property Expediente|null $expediente
 */
class CitasAgenda extends Model
{
    use SoftDeletes;

    protected $table = 'citas_agenda';

    protected $casts = [
        'expediente_id' => 'int',
        'abogado_id' => 'int',
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
    ];

    protected $fillable = [
        'expediente_id',
        'abogado_id',
        'nombre_interesado',
        'tipo_cita',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estatus',
    ];

    public function abogado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'abogado_id');
    }

    public function user(): BelongsTo
    {
        return $this->abogado();
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
}
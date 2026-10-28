<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FoliosIncidencia
 *
 * @property int $id
 * @property int $lote_folio_id
 * @property int|null $escritura_id
 * @property int $numero_folio
 * @property string $motivo
 * @property string|null $descripcion_detalle
 * @property int $usuario_reporta_id
 * @property Carbon $fecha_incidencia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Escritura|null $escritura
 * @property LotesFolio $loteFolio
 * @property User $usuarioReporta
 */
class FoliosIncidencia extends Model
{
    use SoftDeletes;

    protected $table = 'folios_incidencias';

    protected $casts = [
        'lote_folio_id' => 'int',
        'escritura_id' => 'int',
        'numero_folio' => 'int',
        'usuario_reporta_id' => 'int',
        'fecha_incidencia' => 'datetime',
    ];

    protected $fillable = [
        'lote_folio_id',
        'escritura_id',
        'numero_folio',
        'motivo',
        'descripcion_detalle',
        'usuario_reporta_id',
        'fecha_incidencia',
    ];

    public function escritura(): BelongsTo
    {
        return $this->belongsTo(Escritura::class);
    }

    public function loteFolio(): BelongsTo
    {
        return $this->belongsTo(LotesFolio::class, 'lote_folio_id');
    }

    public function lotes_folio(): BelongsTo
    {
        return $this->loteFolio();
    }

    public function usuarioReporta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_reporta_id');
    }

    public function user(): BelongsTo
    {
        return $this->usuarioReporta();
    }
}
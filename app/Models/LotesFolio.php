<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LotesFolio
 *
 * @property int $id
 * @property int $numero_volumen_libro
 * @property int $folio_inicial
 * @property int $folio_final
 * @property int $folios_disponibles
 * @property Carbon $fecha_autorizacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|Escritura[] $escrituras
 * @property Collection|FoliosIncidencia[] $foliosIncidencias
 */
class LotesFolio extends Model
{
    use SoftDeletes;

    protected $table = 'lotes_folios';

    protected $casts = [
        'numero_volumen_libro' => 'int',
        'folio_inicial' => 'int',
        'folio_final' => 'int',
        'folios_disponibles' => 'int',
        'fecha_autorizacion' => 'date',
    ];

    protected $fillable = [
        'numero_volumen_libro',
        'folio_inicial',
        'folio_final',
        'folios_disponibles',
        'fecha_autorizacion',
    ];

    public function escrituras(): HasMany
    {
        return $this->hasMany(Escritura::class, 'lote_folio_id');
    }

    public function foliosIncidencias(): HasMany
    {
        return $this->hasMany(FoliosIncidencia::class, 'lote_folio_id');
    }

    public function folios_incidencias(): HasMany
    {
        return $this->foliosIncidencias();
    }
}
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Escritura
 *
 * @property int $id
 * @property int $numero_escritura
 * @property int $expediente_id
 * @property int $lote_folio_id
 * @property int $volumen
 * @property int $folio_inicio
 * @property int $folio_fin
 * @property int $cantidad_folios_usados
 * @property string|null $forma_liquidacion_partes
 * @property Carbon $fecha_firma
 * @property bool $firmas_completas
 * @property bool $engargolada_lista
 * @property Carbon|null $fecha_ingreso_rpp
 * @property Carbon|null $fecha_inscripcion_rpp
 * @property string|null $folio_real_rpp
 * @property Carbon|null $fecha_entrega_cliente
 * @property string|null $copia_archivo_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Expediente $expediente
 * @property LotesFolio $loteFolio
 * @property Collection|DeclaranotEnvio[] $declaranotEnvios
 * @property Collection|FoliosIncidencia[] $foliosIncidencias
 * @property Collection|TestimoniosExpedido[] $testimoniosExpedidos
 */
class Escritura extends Model
{
    use SoftDeletes;

    protected $table = 'escrituras';

    protected $casts = [
        'numero_escritura' => 'int',
        'expediente_id' => 'int',
        'lote_folio_id' => 'int',
        'volumen' => 'int',
        'folio_inicio' => 'int',
        'folio_fin' => 'int',
        'cantidad_folios_usados' => 'int',
        'fecha_firma' => 'date',
        'firmas_completas' => 'bool',
        'engargolada_lista' => 'bool',
        'fecha_ingreso_rpp' => 'date',
        'fecha_inscripcion_rpp' => 'date',
        'fecha_entrega_cliente' => 'date',
    ];

    protected $fillable = [
        'numero_escritura',
        'expediente_id',
        'lote_folio_id',
        'volumen',
        'folio_inicio',
        'folio_fin',
        'cantidad_folios_usados',
        'forma_liquidacion_partes',
        'fecha_firma',
        'firmas_completas',
        'engargolada_lista',
        'fecha_ingreso_rpp',
        'fecha_inscripcion_rpp',
        'folio_real_rpp',
        'fecha_entrega_cliente',
        'copia_archivo_url',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function loteFolio(): BelongsTo
    {
        return $this->belongsTo(LotesFolio::class, 'lote_folio_id');
    }

    public function lotes_folio(): BelongsTo
    {
        return $this->loteFolio();
    }

    public function declaranotEnvios(): HasMany
    {
        return $this->hasMany(DeclaranotEnvio::class);
    }

    public function declaranot_envios(): HasMany
    {
        return $this->declaranotEnvios();
    }

    public function foliosIncidencias(): HasMany
    {
        return $this->hasMany(FoliosIncidencia::class);
    }

    public function folios_incidencias(): HasMany
    {
        return $this->foliosIncidencias();
    }

    public function testimoniosExpedidos(): HasMany
    {
        return $this->hasMany(TestimoniosExpedido::class);
    }

    public function testimonios_expedidos(): HasMany
    {
        return $this->testimoniosExpedidos();
    }
}
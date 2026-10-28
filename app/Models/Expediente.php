<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Expediente
 *
 * @property int $id
 * @property string $numero_expediente
 * @property int|null $presupuesto_id
 * @property int $tipo_operacion_id
 * @property int $institucion_credito_id
 * @property int $municipio_id
 * @property int $abogado_id
 * @property int|null $secretaria_id
 * @property Carbon $fecha_apertura
 * @property Carbon|null $fecha_limite_sla
 * @property bool $requiere_factura_hon
 * @property string $estatus_proceso
 * @property string|null $observaciones
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property User $abogado
 * @property User|null $secretaria
 * @property InstitucionesCredito $institucionesCredito
 * @property Municipio $municipio
 * @property Presupuesto|null $presupuesto
 * @property TiposOperacion $tiposOperacion
 * @property Collection|Avaluo[] $avaluos
 * @property Collection|AvisosPreventivo[] $avisosPreventivos
 * @property Collection|CitasAgenda[] $citasAgendas
 * @property Escritura|null $escritura
 * @property Collection|ExpedienteCompareciente[] $expedienteComparecientes
 * @property Collection|ExpedienteDocumento[] $expedienteDocumentos
 * @property Collection|Inmueble[] $inmuebles
 * @property Collection|PagosGestoria[] $pagosGestoria
 * @property Collection|RecibosIngreso[] $recibosIngresos
 */
class Expediente extends Model
{
    use SoftDeletes;

    protected $table = 'expedientes';

    protected $casts = [
        'presupuesto_id' => 'int',
        'tipo_operacion_id' => 'int',
        'institucion_credito_id' => 'int',
        'municipio_id' => 'int',
        'abogado_id' => 'int',
        'secretaria_id' => 'int',
        'fecha_apertura' => 'date',
        'fecha_limite_sla' => 'date',
        'requiere_factura_hon' => 'bool',
    ];

    protected $fillable = [
        'numero_expediente',
        'presupuesto_id',
        'tipo_operacion_id',
        'institucion_credito_id',
        'municipio_id',
        'abogado_id',
        'secretaria_id',
        'fecha_apertura',
        'fecha_limite_sla',
        'requiere_factura_hon',
        'estatus_proceso',
        'observaciones',
    ];

    public function abogado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'abogado_id');
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretaria_id');
    }

    public function user(): BelongsTo
    {
        return $this->abogado();
    }

    public function institucionesCredito(): BelongsTo
    {
        return $this->belongsTo(InstitucionesCredito::class, 'institucion_credito_id');
    }

    public function instituciones_credito(): BelongsTo
    {
        return $this->institucionesCredito();
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function tiposOperacion(): BelongsTo
    {
        return $this->belongsTo(TiposOperacion::class, 'tipo_operacion_id');
    }

    public function tipos_operacion(): BelongsTo
    {
        return $this->tiposOperacion();
    }

    public function avaluos(): HasMany
    {
        return $this->hasMany(Avaluo::class);
    }

    public function avisosPreventivos(): HasMany
    {
        return $this->hasMany(AvisosPreventivo::class);
    }

    public function avisos_preventivos(): HasMany
    {
        return $this->avisosPreventivos();
    }

    public function citasAgendas(): HasMany
    {
        return $this->hasMany(CitasAgenda::class);
    }

    public function citas_agendas(): HasMany
    {
        return $this->citasAgendas();
    }

    public function escritura(): HasOne
    {
        return $this->hasOne(Escritura::class);
    }

    public function expedienteComparecientes(): HasMany
    {
        return $this->hasMany(ExpedienteCompareciente::class);
    }

    public function expediente_comparecientes(): HasMany
    {
        return $this->expedienteComparecientes();
    }

    public function expedienteDocumentos(): HasMany
    {
        return $this->hasMany(ExpedienteDocumento::class);
    }

    public function expediente_documentos(): HasMany
    {
        return $this->expedienteDocumentos();
    }

    public function inmuebles(): HasMany
    {
        return $this->hasMany(Inmueble::class);
    }

    public function pagosGestoria(): HasMany
    {
        return $this->hasMany(PagosGestoria::class);
    }

    public function pagos_gestoria(): HasMany
    {
        return $this->pagosGestoria();
    }

    public function recibosIngresos(): HasMany
    {
        return $this->hasMany(RecibosIngreso::class);
    }

    public function recibos_ingresos(): HasMany
    {
        return $this->recibosIngresos();
    }
}
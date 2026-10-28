<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Inmueble
 *
 * @property int $id
 * @property int $expediente_id
 * @property int $municipio_id
 * @property string|null $cuenta_predial
 * @property string|null $clave_catastral
 * @property string $direccion_completa
 * @property string|null $superficie_terreno_m2
 * @property string|null $superficie_construccion_m2
 * @property string|null $medidas_colindancias
 * @property string|null $antecedente_escritura
 * @property string|null $folio_real_antecedente
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Expediente $expediente
 * @property Municipio $municipio
 */
class Inmueble extends Model
{
    use SoftDeletes;

    protected $table = 'inmuebles';

    protected $casts = [
        'expediente_id' => 'int',
        'municipio_id' => 'int',
        'superficie_terreno_m2' => 'decimal:2',
        'superficie_construccion_m2' => 'decimal:2',
    ];

    protected $fillable = [
        'expediente_id',
        'municipio_id',
        'cuenta_predial',
        'clave_catastral',
        'direccion_completa',
        'superficie_terreno_m2',
        'superficie_construccion_m2',
        'medidas_colindancias',
        'antecedente_escritura',
        'folio_real_antecedente',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
}
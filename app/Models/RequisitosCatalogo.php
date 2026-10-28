<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequisitosCatalogo
 *
 * @property int $id
 * @property string $nombre_documento
 * @property string|null $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|ExpedienteDocumento[] $expedienteDocumentos
 * @property Collection|OperacionRequisito[] $operacionRequisitos
 */
class RequisitosCatalogo extends Model
{
    use SoftDeletes;

    protected $table = 'requisitos_catalogo';

    protected $fillable = [
        'nombre_documento',
        'descripcion',
    ];

    public function expedienteDocumentos(): HasMany
    {
        return $this->hasMany(ExpedienteDocumento::class, 'requisito_id');
    }

    public function expediente_documentos(): HasMany
    {
        return $this->expedienteDocumentos();
    }

    public function operacionRequisitos(): HasMany
    {
        return $this->hasMany(OperacionRequisito::class, 'requisito_id');
    }

    public function operacion_requisitos(): HasMany
    {
        return $this->operacionRequisitos();
    }
}
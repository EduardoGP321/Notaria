<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Puesto
 *
 * @property int $id
 * @property string $nombre
 * @property string $clave
 * @property string|null $descripcion
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|User[] $users
 */
class Puesto extends Model
{
    use SoftDeletes;

    protected $table = 'puestos';

    protected $casts = [
        'activo' => 'bool',
    ];

    protected $fillable = [
        'nombre',
        'clave',
        'descripcion',
        'activo',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'puesto_user')
            ->withPivot('id', 'fecha_asignacion', 'es_principal')
            ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PeritosValuador
 *
 * @property int $id
 * @property string $nombre_completo
 * @property string|null $cedula_registro
 * @property string|null $telefono
 * @property string|null $email
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|Avaluo[] $avaluos
 */
class PeritosValuador extends Model
{
    use SoftDeletes;

    protected $table = 'peritos_valuadores';

    protected $casts = [
        'activo' => 'bool',
    ];

    protected $fillable = [
        'nombre_completo',
        'cedula_registro',
        'telefono',
        'email',
        'activo',
    ];

    public function avaluos(): HasMany
    {
        return $this->hasMany(Avaluo::class, 'perito_id');
    }
}
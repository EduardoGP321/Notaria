<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PuestoUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $puesto_id
 * @property Carbon $fecha_asignacion
 * @property bool $es_principal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Puesto $puesto
 * @property User $user
 */
class PuestoUser extends Model
{
    protected $table = 'puesto_user';

    protected $casts = [
        'user_id' => 'int',
        'puesto_id' => 'int',
        'fecha_asignacion' => 'date',
        'es_principal' => 'bool',
    ];

    protected $fillable = [
        'user_id',
        'puesto_id',
        'fecha_asignacion',
        'es_principal',
    ];

    public function puesto(): BelongsTo
    {
        return $this->belongsTo(Puesto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
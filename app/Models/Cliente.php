<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cliente
 *
 * @property int $id
 * @property string $tipo_persona
 * @property string $nombre_razon_social
 * @property string|null $rfc
 * @property string|null $curp
 * @property string|null $estado_civil
 * @property string $telefono
 * @property string|null $email
 * @property string|null $domicilio_fiscal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|DeclaranotEnvio[] $declaranotEnvios
 * @property Collection|ExpedienteCompareciente[] $expedienteComparecientes
 * @property Collection|Presupuesto[] $presupuestos
 * @property Collection|RecibosIngreso[] $recibosIngresos
 * @property Collection|TestimoniosExpedido[] $testimoniosExpedidos
 */
class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $casts = [
        'tipo_persona' => 'string',
        'estado_civil' => 'string',
    ];

    protected $fillable = [
        'tipo_persona',
        'nombre_razon_social',
        'rfc',
        'curp',
        'estado_civil',
        'telefono',
        'email',
        'domicilio_fiscal',
    ];

    public function declaranotEnvios(): HasMany
    {
        return $this->hasMany(DeclaranotEnvio::class, 'vendedor_cliente_id');
    }

    public function declaranot_envios(): HasMany
    {
        return $this->declaranotEnvios();
    }

    public function expedienteComparecientes(): HasMany
    {
        return $this->hasMany(ExpedienteCompareciente::class);
    }

    public function expediente_comparecientes(): HasMany
    {
        return $this->expedienteComparecientes();
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class);
    }

    public function recibosIngresos(): HasMany
    {
        return $this->hasMany(RecibosIngreso::class);
    }

    public function recibos_ingresos(): HasMany
    {
        return $this->recibosIngresos();
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
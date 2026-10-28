<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $telefono
 * @property string|null $iniciales
 * @property bool $activo
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property Collection|CitasAgenda[] $citasAgendas
 * @property Collection|CortesCaja[] $cortesCajas
 * @property Collection|Expediente[] $expedientesComoAbogado
 * @property Collection|Expediente[] $expedientesComoSecretaria
 * @property Collection|FoliosIncidencia[] $foliosIncidencias
 * @property Collection|PagosGestoria[] $pagosGestoria
 * @property Collection|Presupuesto[] $presupuestos
 * @property Collection|Puesto[] $puestos
 * @property Collection|RecibosIngreso[] $recibosIngresos
 * @property Collection|TestimoniosExpedido[] $testimoniosExpedidos
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'bool',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'telefono',
        'iniciales',
        'activo',
        'remember_token',
    ];

    public function citasAgendas(): HasMany
    {
        return $this->hasMany(CitasAgenda::class, 'abogado_id');
    }

    public function citas_agendas(): HasMany
    {
        return $this->citasAgendas();
    }

    public function cortesCajas(): HasMany
    {
        return $this->hasMany(CortesCaja::class, 'usuario_elabora_id');
    }

    public function cortes_cajas(): HasMany
    {
        return $this->cortesCajas();
    }

    public function expedientesComoAbogado(): HasMany
    {
        return $this->hasMany(Expediente::class, 'abogado_id');
    }

    public function expedientesComoSecretaria(): HasMany
    {
        return $this->hasMany(Expediente::class, 'secretaria_id');
    }

    public function expedientes(): HasMany
    {
        return $this->expedientesComoAbogado();
    }

    public function foliosIncidencias(): HasMany
    {
        return $this->hasMany(FoliosIncidencia::class, 'usuario_reporta_id');
    }

    public function folios_incidencias(): HasMany
    {
        return $this->foliosIncidencias();
    }

    public function pagosGestoriaComoAdmin(): HasMany
    {
        return $this->hasMany(PagosGestoria::class, 'admin_caja_id');
    }

    public function pagosGestoriaComoGestor(): HasMany
    {
        return $this->hasMany(PagosGestoria::class, 'gestor_id');
    }

    public function pagosGestoriaComoSecretaria(): HasMany
    {
        return $this->hasMany(PagosGestoria::class, 'secretaria_recibe_id');
    }

    public function pagosGestoria(): HasMany
    {
        return $this->pagosGestoriaComoSecretaria();
    }

    public function pagos_gestoria(): HasMany
    {
        return $this->pagosGestoria();
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class, 'abogado_id');
    }

    public function puestos(): BelongsToMany
    {
        return $this->belongsToMany(Puesto::class, 'puesto_user')
            ->withPivot('id', 'fecha_asignacion', 'es_principal')
            ->withTimestamps();
    }

    public function recibosIngresos(): HasMany
    {
        return $this->hasMany(RecibosIngreso::class, 'usuario_cobro_id');
    }

    public function recibos_ingresos(): HasMany
    {
        return $this->recibosIngresos();
    }

    public function testimoniosExpedidos(): HasMany
    {
        return $this->hasMany(TestimoniosExpedido::class, 'usuario_entrega_id');
    }

    public function testimonios_expedidos(): HasMany
    {
        return $this->testimoniosExpedidos();
    }
}
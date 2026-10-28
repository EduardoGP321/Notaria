<?php

namespace App\Observers;

use App\Models\Expediente;
use App\Models\InstitucionesCredito;
use Carbon\Carbon;

class ExpedienteObserver
{
    /**
     * Handle the Expediente "saving" event.
     */
    public function saving(Expediente $expediente): void
    {
        // Si tiene institución de crédito y fecha de apertura, calcular fecha_limite_sla
        if ($expediente->institucion_credito_id && $expediente->fecha_apertura) {
            if (empty($expediente->fecha_limite_sla) || $expediente->isDirty(['institucion_credito_id', 'fecha_apertura'])) {
                $banco = $expediente->institucionesCredito 
                    ?? InstitucionesCredito::find($expediente->institucion_credito_id);

                if ($banco && $banco->dias_limite_rpp) {
                    $fechaApertura = Carbon::parse($expediente->fecha_apertura);
                    $expediente->fecha_limite_sla = $fechaApertura->copy()->addDays((int) $banco->dias_limite_rpp);
                }
            }
        }
    }
}

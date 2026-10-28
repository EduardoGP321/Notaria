<?php

namespace App\Http\Controllers;

use App\Models\RecibosIngreso;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReciboFoliadoController extends Controller
{
    /**
     * Muestra la vista optimizada de impresión para papel membretado/foliado
     * y marca automáticamente el registro como impreso.
     */
    public function imprimir(RecibosIngreso $record): View
    {
        $record->loadMissing([
            'cliente',
            'expediente',
            'usuarioCobro',
        ]);

        // Actualizar estatus de impresión en papel foliado
        if (! $record->impreso_en_foliado) {
            $record->update(['impreso_en_foliado' => true]);
        }

        return view('recibos.impresion-foliado', [
            'recibo' => $record,
        ]);
    }
}

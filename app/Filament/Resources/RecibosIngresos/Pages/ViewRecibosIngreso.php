<?php

namespace App\Filament\Resources\RecibosIngresos\Pages;

use App\Filament\Resources\RecibosIngresos\RecibosIngresoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRecibosIngreso extends ViewRecord
{
    protected static string $resource = RecibosIngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Inmuebles\Pages;

use App\Filament\Resources\Inmuebles\InmuebleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInmueble extends ViewRecord
{
    protected static string $resource = InmuebleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

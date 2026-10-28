<?php

namespace App\Filament\Resources\Expedientes\Pages;

use App\Filament\Resources\Expedientes\ExpedienteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExpediente extends ViewRecord
{
    protected static string $resource = ExpedienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

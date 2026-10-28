<?php

namespace App\Filament\Resources\CortesCajas\Pages;

use App\Filament\Resources\CortesCajas\CortesCajaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCortesCaja extends ViewRecord
{
    protected static string $resource = CortesCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

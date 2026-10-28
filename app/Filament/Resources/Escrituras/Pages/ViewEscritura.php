<?php

namespace App\Filament\Resources\Escrituras\Pages;

use App\Filament\Resources\Escrituras\EscrituraResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEscritura extends ViewRecord
{
    protected static string $resource = EscrituraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

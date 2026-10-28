<?php

namespace App\Filament\Resources\LotesFolios\Pages;

use App\Filament\Resources\LotesFolios\LotesFolioResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLotesFolio extends ViewRecord
{
    protected static string $resource = LotesFolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

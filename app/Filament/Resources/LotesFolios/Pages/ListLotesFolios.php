<?php

namespace App\Filament\Resources\LotesFolios\Pages;

use App\Filament\Resources\LotesFolios\LotesFolioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLotesFolios extends ListRecords
{
    protected static string $resource = LotesFolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Escrituras\Pages;

use App\Filament\Resources\Escrituras\EscrituraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEscrituras extends ListRecords
{
    protected static string $resource = EscrituraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

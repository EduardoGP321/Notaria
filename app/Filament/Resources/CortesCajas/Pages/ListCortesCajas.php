<?php

namespace App\Filament\Resources\CortesCajas\Pages;

use App\Filament\Resources\CortesCajas\CortesCajaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCortesCajas extends ListRecords
{
    protected static string $resource = CortesCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\RecibosIngresos\Pages;

use App\Filament\Resources\RecibosIngresos\RecibosIngresoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecibosIngresos extends ListRecords
{
    protected static string $resource = RecibosIngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\RequisitosCatalogos\Pages;

use App\Filament\Resources\RequisitosCatalogos\RequisitosCatalogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRequisitosCatalogos extends ManageRecords
{
    protected static string $resource = RequisitosCatalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Requisito')
                ->modalWidth('lg'),
        ];
    }
}
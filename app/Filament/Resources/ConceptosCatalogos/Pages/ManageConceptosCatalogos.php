<?php

namespace App\Filament\Resources\ConceptosCatalogos\Pages;

use App\Filament\Resources\ConceptosCatalogos\ConceptosCatalogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConceptosCatalogos extends ManageRecords
{
    protected static string $resource = ConceptosCatalogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Concepto')
                ->modalWidth('xl'),
        ];
    }
}
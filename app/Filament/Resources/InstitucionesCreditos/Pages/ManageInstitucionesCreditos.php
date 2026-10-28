<?php

namespace App\Filament\Resources\InstitucionesCreditos\Pages;

use App\Filament\Resources\InstitucionesCreditos\InstitucionesCreditoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInstitucionesCreditos extends ManageRecords
{
    protected static string $resource = InstitucionesCreditoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Institución')
                ->modalWidth('lg'),
        ];
    }
}
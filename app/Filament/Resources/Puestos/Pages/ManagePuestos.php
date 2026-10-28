<?php

namespace App\Filament\Resources\Puestos\Pages;

use App\Filament\Resources\Puestos\PuestoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePuestos extends ManageRecords
{
    protected static string $resource = PuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Puesto')
                ->modalWidth('lg'),
        ];
    }
}
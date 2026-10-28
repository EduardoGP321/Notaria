<?php

namespace App\Filament\Resources\PeritosValuadors\Pages;

use App\Filament\Resources\PeritosValuadors\PeritosValuadorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePeritosValuadors extends ManageRecords
{
    protected static string $resource = PeritosValuadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Perito')
                ->modalWidth('lg'),
        ];
    }
}
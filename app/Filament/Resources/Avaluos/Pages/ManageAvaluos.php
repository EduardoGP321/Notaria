<?php

namespace App\Filament\Resources\Avaluos\Pages;

use App\Filament\Resources\Avaluos\AvaluoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAvaluos extends ManageRecords
{
    protected static string $resource = AvaluoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Avalúo')
                ->modalWidth('2xl'),
        ];
    }
}
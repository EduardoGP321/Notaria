<?php

namespace App\Filament\Resources\DeclaranotEnvios\Pages;

use App\Filament\Resources\DeclaranotEnvios\DeclaranotEnvioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDeclaranotEnvios extends ManageRecords
{
    protected static string $resource = DeclaranotEnvioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Registrar Declaranot')
                ->modalWidth('xl'),
        ];
    }
}
<?php

namespace App\Filament\Resources\AvisosPreventivos\Pages;

use App\Filament\Resources\AvisosPreventivos\AvisosPreventivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAvisosPreventivos extends ManageRecords
{
    protected static string $resource = AvisosPreventivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Aviso Preventivo')
                ->modalWidth('xl'),
        ];
    }
}
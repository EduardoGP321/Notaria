<?php

namespace App\Filament\Resources\CitasAgendas\Pages;

use App\Filament\Resources\CitasAgendas\CitasAgendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCitasAgendas extends ManageRecords
{
    protected static string $resource = CitasAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agendar Cita')
                ->modalWidth('xl'),
        ];
    }
}
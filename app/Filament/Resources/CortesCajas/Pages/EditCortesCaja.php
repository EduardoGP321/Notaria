<?php

namespace App\Filament\Resources\CortesCajas\Pages;

use App\Filament\Resources\CortesCajas\CortesCajaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCortesCaja extends EditRecord
{
    protected static string $resource = CortesCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

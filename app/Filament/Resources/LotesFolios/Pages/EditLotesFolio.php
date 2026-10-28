<?php

namespace App\Filament\Resources\LotesFolios\Pages;

use App\Filament\Resources\LotesFolios\LotesFolioResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLotesFolio extends EditRecord
{
    protected static string $resource = LotesFolioResource::class;

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

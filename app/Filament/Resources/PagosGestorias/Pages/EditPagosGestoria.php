<?php

namespace App\Filament\Resources\PagosGestorias\Pages;

use App\Filament\Resources\PagosGestorias\PagosGestoriaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPagosGestoria extends EditRecord
{
    protected static string $resource = PagosGestoriaResource::class;

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

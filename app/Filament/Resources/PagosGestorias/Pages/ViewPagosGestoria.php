<?php

namespace App\Filament\Resources\PagosGestorias\Pages;

use App\Filament\Resources\PagosGestorias\PagosGestoriaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPagosGestoria extends ViewRecord
{
    protected static string $resource = PagosGestoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

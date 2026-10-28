<?php

namespace App\Filament\Resources\PagosGestorias\Pages;

use App\Filament\Resources\PagosGestorias\PagosGestoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPagosGestorias extends ListRecords
{
    protected static string $resource = PagosGestoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\TiposOperacions\Pages;

use App\Filament\Resources\TiposOperacions\TiposOperacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTiposOperacions extends ManageRecords
{
    protected static string $resource = TiposOperacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

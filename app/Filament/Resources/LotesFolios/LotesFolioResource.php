<?php

namespace App\Filament\Resources\LotesFolios;

use App\Filament\Resources\LotesFolios\Pages\CreateLotesFolio;
use App\Filament\Resources\LotesFolios\Pages\EditLotesFolio;
use App\Filament\Resources\LotesFolios\Pages\ListLotesFolios;
use App\Filament\Resources\LotesFolios\Pages\ViewLotesFolio;
use App\Filament\Resources\LotesFolios\Schemas\LotesFolioForm;
use App\Filament\Resources\LotesFolios\Schemas\LotesFolioInfolist;
use App\Filament\Resources\LotesFolios\Tables\LotesFoliosTable;
use App\Models\LotesFolio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LotesFolioResource extends Resource
{
    protected static ?string $model = LotesFolio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Protocolo y Folios';

    protected static ?string $modelLabel = 'Lote de Folios';

    protected static ?string $pluralModelLabel = 'Lotes de Folios';

    protected static ?string $navigationLabel = 'Lotes de Folios';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return LotesFolioForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LotesFolioInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LotesFoliosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'id',
                'numero_volumen_libro',
                'folio_inicial',
                'folio_final',
                'folios_disponibles',
                'fecha_autorizacion',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLotesFolios::route('/'),
            'create' => CreateLotesFolio::route('/create'),
            'view' => ViewLotesFolio::route('/{record}'),
            'edit' => EditLotesFolio::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
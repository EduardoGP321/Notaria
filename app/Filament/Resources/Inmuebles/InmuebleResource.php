<?php

namespace App\Filament\Resources\Inmuebles;

use App\Filament\Resources\Inmuebles\Pages\CreateInmueble;
use App\Filament\Resources\Inmuebles\Pages\EditInmueble;
use App\Filament\Resources\Inmuebles\Pages\ListInmuebles;
use App\Filament\Resources\Inmuebles\Pages\ViewInmueble;
use App\Filament\Resources\Inmuebles\Schemas\InmuebleForm;
use App\Filament\Resources\Inmuebles\Schemas\InmuebleInfolist;
use App\Filament\Resources\Inmuebles\Tables\InmueblesTable;
use App\Models\Inmueble;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InmuebleResource extends Resource
{
    protected static ?string $model = Inmueble::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|\UnitEnum|null $navigationGroup = 'Directorio y Catálogos';

    protected static ?string $modelLabel = 'Inmueble';

    protected static ?string $pluralModelLabel = 'Inmuebles';

    protected static ?string $navigationLabel = 'Inmuebles';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return InmuebleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InmuebleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InmueblesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'expediente',
                'municipio',
            ])
            ->select([
                'id',
                'expediente_id',
                'municipio_id',
                'cuenta_predial',
                'clave_catastral',
                'direccion_completa',
                'superficie_terreno_m2',
                'superficie_construccion_m2',
                'medidas_colindancias',
                'antecedente_escritura',
                'folio_real_antecedente',
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
            'index' => ListInmuebles::route('/'),
            'create' => CreateInmueble::route('/create'),
            'view' => ViewInmueble::route('/{record}'),
            'edit' => EditInmueble::route('/{record}/edit'),
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
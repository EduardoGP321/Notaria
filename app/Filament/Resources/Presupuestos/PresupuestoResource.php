<?php

namespace App\Filament\Resources\Presupuestos;

use App\Filament\Resources\Presupuestos\Pages\CreatePresupuesto;
use App\Filament\Resources\Presupuestos\Pages\EditPresupuesto;
use App\Filament\Resources\Presupuestos\Pages\ListPresupuestos;
use App\Filament\Resources\Presupuestos\Pages\ViewPresupuesto;
use App\Filament\Resources\Presupuestos\RelationManagers\DetallesRelationManager;
use App\Filament\Resources\Presupuestos\Schemas\PresupuestoForm;
use App\Filament\Resources\Presupuestos\Schemas\PresupuestoInfolist;
use App\Filament\Resources\Presupuestos\Tables\PresupuestosTable;
use App\Models\Presupuesto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresupuestoResource extends Resource
{
    protected static ?string $model = Presupuesto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string|\UnitEnum|null $navigationGroup = 'Comercial y Cotización';

    protected static ?string $modelLabel = 'Presupuesto';

    protected static ?string $pluralModelLabel = 'Presupuestos';

    protected static ?string $navigationLabel = 'Presupuestos';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PresupuestoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PresupuestoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresupuestosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'cliente',
                'abogado',
                'tiposOperacion',
                'municipio',
            ])
            ->select([
                'id',
                'folio_presupuesto',
                'cliente_id',
                'abogado_id',
                'tipo_operacion_id',
                'municipio_id',
                'valor_operacion',
                'porcentaje_aplicado',
                'subtotal_impuestos_der',
                'honorarios_notariales',
                'iva_honorarios',
                'total_presupuesto',
                'estatus',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetallesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresupuestos::route('/'),
            'create' => CreatePresupuesto::route('/create'),
            'view' => ViewPresupuesto::route('/{record}'),
            'edit' => EditPresupuesto::route('/{record}/edit'),
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

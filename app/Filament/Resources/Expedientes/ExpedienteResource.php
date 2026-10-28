<?php

namespace App\Filament\Resources\Expedientes;

use App\Filament\Resources\Expedientes\Pages\CreateExpediente;
use App\Filament\Resources\Expedientes\Pages\EditExpediente;
use App\Filament\Resources\Expedientes\Pages\ListExpedientes;
use App\Filament\Resources\Expedientes\Pages\ViewExpediente;
use App\Filament\Resources\Expedientes\RelationManagers\AvaluosRelationManager;
use App\Filament\Resources\Expedientes\RelationManagers\AvisosPreventivosRelationManager;
use App\Filament\Resources\Expedientes\RelationManagers\ComparecientesRelationManager;
use App\Filament\Resources\Expedientes\RelationManagers\DocumentosRelationManager;
use App\Filament\Resources\Expedientes\Schemas\ExpedienteForm;
use App\Filament\Resources\Expedientes\Schemas\ExpedienteInfolist;
use App\Filament\Resources\Expedientes\Tables\ExpedientesTable;
use App\Models\Expediente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpedienteResource extends Resource
{
    protected static ?string $model = Expediente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Operación Notarial';

    protected static ?string $modelLabel = 'Expediente';

    protected static ?string $pluralModelLabel = 'Expedientes';

    protected static ?string $navigationLabel = 'Expedientes';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ExpedienteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExpedienteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpedientesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'presupuesto',
                'tiposOperacion',
                'institucionesCredito',
                'municipio',
                'abogado',
                'secretaria',
            ])
            ->select([
                'id',
                'numero_expediente',
                'presupuesto_id',
                'tipo_operacion_id',
                'institucion_credito_id',
                'municipio_id',
                'abogado_id',
                'secretaria_id',
                'fecha_apertura',
                'fecha_limite_sla',
                'requiere_factura_hon',
                'estatus_proceso',
                'observaciones',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ComparecientesRelationManager::class,
            DocumentosRelationManager::class,
            AvisosPreventivosRelationManager::class,
            AvaluosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpedientes::route('/'),
            'create' => CreateExpediente::route('/create'),
            'view' => ViewExpediente::route('/{record}'),
            'edit' => EditExpediente::route('/{record}/edit'),
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

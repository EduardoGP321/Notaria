<?php

namespace App\Filament\Resources\CortesCajas;

use App\Filament\Resources\CortesCajas\Pages\CreateCortesCaja;
use App\Filament\Resources\CortesCajas\Pages\EditCortesCaja;
use App\Filament\Resources\CortesCajas\Pages\ListCortesCajas;
use App\Filament\Resources\CortesCajas\Pages\ViewCortesCaja;
use App\Filament\Resources\CortesCajas\RelationManagers\RecibosIngresosRelationManager;
use App\Filament\Resources\CortesCajas\Schemas\CortesCajaForm;
use App\Filament\Resources\CortesCajas\Schemas\CortesCajaInfolist;
use App\Filament\Resources\CortesCajas\Tables\CortesCajasTable;
use App\Models\CortesCaja;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CortesCajaResource extends Resource
{
    protected static ?string $model = CortesCaja::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finanzas y Caja';

    protected static ?string $modelLabel = 'Corte de Caja';

    protected static ?string $pluralModelLabel = 'Cortes de Caja';

    protected static ?string $navigationLabel = 'Cortes de Caja';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CortesCajaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CortesCajaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CortesCajasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RecibosIngresosRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'usuarioElabora:id,name',
                'recibosIngresos:id,corte_caja_id,folio_recibo_impreso,importe,forma_pago',
            ])
            ->select([
                'id',
                'tipo_corte',
                'fecha_inicio',
                'fecha_fin',
                'total_efectivo',
                'total_bancos',
                'monto_total',
                'usuario_elabora_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCortesCajas::route('/'),
            'create' => CreateCortesCaja::route('/create'),
            'view' => ViewCortesCaja::route('/{record}'),
            'edit' => EditCortesCaja::route('/{record}/edit'),
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
<?php

namespace App\Filament\Resources\RecibosIngresos;

use App\Filament\Resources\RecibosIngresos\Pages\CreateRecibosIngreso;
use App\Filament\Resources\RecibosIngresos\Pages\EditRecibosIngreso;
use App\Filament\Resources\RecibosIngresos\Pages\ListRecibosIngresos;
use App\Filament\Resources\RecibosIngresos\Pages\ViewRecibosIngreso;
use App\Filament\Resources\RecibosIngresos\Schemas\RecibosIngresoForm;
use App\Filament\Resources\RecibosIngresos\Schemas\RecibosIngresoInfolist;
use App\Filament\Resources\RecibosIngresos\Tables\RecibosIngresosTable;
use App\Models\RecibosIngreso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecibosIngresoResource extends Resource
{
    protected static ?string $model = RecibosIngreso::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|\UnitEnum|null $navigationGroup = 'Caja y Cobranza';

    protected static ?string $modelLabel = 'Recibo de Ingreso';

    protected static ?string $pluralModelLabel = 'Recibos de Ingreso';

    protected static ?string $navigationLabel = 'Recibos de Ingreso';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RecibosIngresoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecibosIngresoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecibosIngresosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'expediente',
                'cliente',
                'usuarioCobro',
                'corteCaja',
            ])
            ->select([
                'id',
                'folio_recibo_impreso',
                'expediente_id',
                'cliente_id',
                'usuario_cobro_id',
                'corte_caja_id',
                'nombre_concepto_recibo',
                'importe',
                'forma_pago',
                'banco_referencia',
                'impreso_en_foliado',
                'requiere_cfdi',
                'uuid_factura_notaria',
                'fecha_pago',
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
            'index' => ListRecibosIngresos::route('/'),
            'create' => CreateRecibosIngreso::route('/create'),
            'view' => ViewRecibosIngreso::route('/{record}'),
            'edit' => EditRecibosIngreso::route('/{record}/edit'),
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
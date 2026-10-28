<?php

namespace App\Filament\Resources\PagosGestorias;

use App\Filament\Resources\PagosGestorias\Pages\CreatePagosGestoria;
use App\Filament\Resources\PagosGestorias\Pages\EditPagosGestoria;
use App\Filament\Resources\PagosGestorias\Pages\ListPagosGestorias;
use App\Filament\Resources\PagosGestorias\Pages\ViewPagosGestoria;
use App\Filament\Resources\PagosGestorias\Schemas\PagosGestoriaForm;
use App\Filament\Resources\PagosGestorias\Schemas\PagosGestoriaInfolist;
use App\Filament\Resources\PagosGestorias\Tables\PagosGestoriasTable;
use App\Models\PagosGestoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagosGestoriaResource extends Resource
{
    protected static ?string $model = PagosGestoria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Gestoría y Trámites';

    protected static ?string $modelLabel = 'Pago de Gestoría';

    protected static ?string $pluralModelLabel = 'Pagos de Gestoría';

    protected static ?string $navigationLabel = 'Pagos de Gestoría';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PagosGestoriaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PagosGestoriaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagosGestoriasTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'expediente',
                'concepto',
                'adminCaja',
                'gestor',
                'secretariaRecibe',
            ])
            ->select([
                'id',
                'expediente_id',
                'concepto_id',
                'linea_captura_referencia',
                'banco_pago',
                'monto_solicitado',
                'admin_caja_id',
                'gestor_id',
                'secretaria_recibe_id',
                'fecha_solicitud_recurso',
                'fecha_entrega_gestor',
                'fecha_pago_dependencia',
                'fecha_factura_secretaria',
                'comprobante_pago_url',
                'factura_catastro_pdf_url',
                'estatus_pago',
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
            'index' => ListPagosGestorias::route('/'),
            'create' => CreatePagosGestoria::route('/create'),
            'view' => ViewPagosGestoria::route('/{record}'),
            'edit' => EditPagosGestoria::route('/{record}/edit'),
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
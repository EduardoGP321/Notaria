<?php

namespace App\Filament\Resources\Escrituras;

use App\Filament\Resources\Escrituras\Pages\CreateEscritura;
use App\Filament\Resources\Escrituras\Pages\EditEscritura;
use App\Filament\Resources\Escrituras\Pages\ListEscrituras;
use App\Filament\Resources\Escrituras\Pages\ViewEscritura;
use App\Filament\Resources\Escrituras\RelationManagers\FoliosIncidenciasRelationManager;
use App\Filament\Resources\Escrituras\RelationManagers\TestimoniosRelationManager;
use App\Filament\Resources\Escrituras\Schemas\EscrituraForm;
use App\Filament\Resources\Escrituras\Schemas\EscrituraInfolist;
use App\Filament\Resources\Escrituras\Tables\EscriturasTable;
use App\Models\Escritura;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EscrituraResource extends Resource
{
    protected static ?string $model = Escritura::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static string|\UnitEnum|null $navigationGroup = 'Operación Notarial';

    protected static ?string $modelLabel = 'Escritura';

    protected static ?string $pluralModelLabel = 'Escrituras';

    protected static ?string $navigationLabel = 'Escrituras';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return EscrituraForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EscrituraInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EscriturasTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'expediente',
                'loteFolio',
            ])
            ->select([
                'id',
                'numero_escritura',
                'expediente_id',
                'lote_folio_id',
                'volumen',
                'folio_inicio',
                'folio_fin',
                'cantidad_folios_usados',
                'forma_liquidacion_partes',
                'fecha_firma',
                'firmas_completas',
                'engargolada_lista',
                'fecha_ingreso_rpp',
                'fecha_inscripcion_rpp',
                'folio_real_rpp',
                'fecha_entrega_cliente',
                'copia_archivo_url',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TestimoniosRelationManager::class,
            FoliosIncidenciasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEscrituras::route('/'),
            'create' => CreateEscritura::route('/create'),
            'view' => ViewEscritura::route('/{record}'),
            'edit' => EditEscritura::route('/{record}/edit'),
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
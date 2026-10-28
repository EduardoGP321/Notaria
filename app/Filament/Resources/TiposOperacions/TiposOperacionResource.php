<?php

namespace App\Filament\Resources\TiposOperacions;

use App\Filament\Resources\TiposOperacions\Pages\ManageTiposOperacions;
use App\Models\TiposOperacion;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TiposOperacionResource extends Resource
{
    protected static ?string $model = TiposOperacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración y Catálogos';

    protected static ?string $modelLabel = 'Tipo de Operación';

    protected static ?string $pluralModelLabel = 'Tipos de Operación';

    protected static ?string $navigationLabel = 'Tipos de Operación';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Definición del Acto Notarial')
                    ->description('Configura las reglas operativas y documentales para este tipo de trámite.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre del Acto Notarial')
                                    ->required()
                                    ->maxLength(120)
                                    ->placeholder('ej. Compraventa de Inmueble con Hipoteca')
                                    ->suffixIcon('heroicon-o-document-text')
                                    ->columnSpanFull(),

                                TextInput::make('plantilla_word_oficio')
                                    ->label('Ruta de Plantilla / Formato')
                                    ->maxLength(255)
                                    ->placeholder('ej. plantillas/compraventa_fovissste_2026.docx')
                                    ->suffixIcon('heroicon-o-document-arrow-up')
                                    ->columnSpanFull(),

                                Toggle::make('requiere_avaluo')
                                    ->label('Requiere Avalúo Pericial')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('requiere_rpp')
                                    ->label('Requiere Inscripción en RPP')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('activo')
                                    ->label('Operación Habilitada')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('nombre')
                    ->label('Acto Notarial')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('requiere_avaluo')
                    ->label('Requiere Avalúo')
                    ->boolean(),

                IconColumn::make('requiere_rpp')
                    ->label('Requiere RPP')
                    ->boolean(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                TextColumn::make('plantilla_word_oficio')
                    ->label('Plantilla')
                    ->placeholder('Sin plantilla')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),

                TernaryFilter::make('requiere_avaluo')
                    ->label('Avalúo')
                    ->placeholder('Todos')
                    ->trueLabel('Requiere avalúo')
                    ->falseLabel('No requiere avalúo'),

                TernaryFilter::make('requiere_rpp')
                    ->label('RPP')
                    ->placeholder('Todos')
                    ->trueLabel('Requiere RPP')
                    ->falseLabel('No requiere RPP'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'id',
                'nombre',
                'requiere_avaluo',
                'requiere_rpp',
                'plantilla_word_oficio',
                'activo',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTiposOperacions::route('/'),
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

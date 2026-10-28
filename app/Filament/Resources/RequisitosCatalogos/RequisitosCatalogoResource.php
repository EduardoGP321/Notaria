<?php

namespace App\Filament\Resources\RequisitosCatalogos;

use App\Filament\Resources\RequisitosCatalogos\Pages\ManageRequisitosCatalogos;
use App\Models\RequisitosCatalogo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequisitosCatalogoResource extends Resource
{
    protected static ?string $model = RequisitosCatalogo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Requisito Documental';

    protected static ?string $pluralModelLabel = 'Requisitos Documentales';

    protected static ?string $navigationLabel = 'Requisitos Documentales';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Definición del Documento Exigido')
                    ->description('Nombre y especificaciones técnicas o jurídicas del requisito para cotejo.')
                    ->schema([
                        TextInput::make('nombre_documento')
                            ->label('Nombre del Documento Requerido')
                            ->required()
                            ->maxLength(150)
                            ->placeholder('ej. Escritura antecedente con sello de RPP')
                            ->suffixIcon('heroicon-o-document-text'),

                        Textarea::make('descripcion')
                            ->label('Especificaciones y Reglas de Cotejo')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('ej. Copia certificada o primer testimonio debidamente inscrito ante el Registro Público de la Propiedad.'),
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
                TextColumn::make('nombre_documento')
                    ->label('Documento')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('descripcion')
                    ->label('Especificaciones')
                    ->limit(60)
                    ->placeholder('Sin descripción'),

                TextColumn::make('created_at')
                    ->label('Fecha de Alta')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('lg'),
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
                'nombre_documento',
                'descripcion',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->orderBy('nombre_documento');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRequisitosCatalogos::route('/'),
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

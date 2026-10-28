<?php

namespace App\Filament\Resources\Municipios;

use App\Filament\Resources\Municipios\Pages\ManageMunicipios;
use App\Models\Municipio;
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

class MunicipioResource extends Resource
{
    protected static ?string $model = Municipio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Municipio';

    protected static ?string $pluralModelLabel = 'Municipios';

    protected static ?string $navigationLabel = 'Municipios';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Municipio y Tasa Tributaria')
                    ->description('Configura los parámetros fiscales de ISABI y plazos promedio para este municipio.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre del Municipio')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ej. San Andrés Cholula o Puebla')
                                    ->suffixIcon('heroicon-o-building-office'),

                                TextInput::make('estado')
                                    ->label('Entidad Federativa')
                                    ->required()
                                    ->maxLength(80)
                                    ->default('Puebla')
                                    ->placeholder('ej. Puebla')
                                    ->suffixIcon('heroicon-o-map'),

                                TextInput::make('porcentaje_isabi')
                                    ->label('Tasa ISABI Municipal')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->default(2.00)
                                    ->suffix('%')
                                    ->placeholder('2.00')
                                    ->suffixIcon('heroicon-o-variable'),

                                TextInput::make('dias_promedio_avaluo')
                                    ->label('Plazo Promedio de Avalúo')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->default(14)
                                    ->placeholder('14')
                                    ->suffixIcon('heroicon-o-clock'),

                                Toggle::make('activo')
                                    ->label('Municipio Habilitado')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpanFull(),
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
                    ->label('Municipio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->sortable(),

                TextColumn::make('porcentaje_isabi')
                    ->label('Tasa ISABI')
                    ->numeric(2, '.', ',')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('dias_promedio_avaluo')
                    ->label('Plazo Avalúo')
                    ->numeric()
                    ->suffix(' días naturales')
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),

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
                'nombre',
                'estado',
                'porcentaje_isabi',
                'dias_promedio_avaluo',
                'activo',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->orderBy('nombre');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMunicipios::route('/'),
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

<?php

namespace App\Filament\Resources\InstitucionesCreditos;

use App\Filament\Resources\InstitucionesCreditos\Pages\ManageInstitucionesCreditos;
use App\Models\InstitucionesCredito;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstitucionesCreditoResource extends Resource
{
    protected static ?string $model = InstitucionesCredito::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos y Configuración';

    protected static ?string $modelLabel = 'Institución de Crédito';

    protected static ?string $pluralModelLabel = 'Instituciones de Crédito';

    protected static ?string $navigationLabel = 'Instituciones de Crédito';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de la Institución y Plazos Registrales')
                    ->description('Configura los plazos límite y alertas previas para inscripción de hipotecas.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la Institución / Acreedor')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ej. Santander o Infonavit')
                                    ->suffixIcon('heroicon-o-building-library')
                                    ->columnSpanFull(),

                                Select::make('tipo')
                                    ->label('Tipo de Entidad')
                                    ->options([
                                        'banco' => 'Banco Comercial',
                                        'instituto_vivienda' => 'Instituto de Vivienda (Infonavit / Fovissste)',
                                        'particular' => 'Acreedor Particular',
                                    ])
                                    ->default('banco')
                                    ->required()
                                    ->suffixIcon('heroicon-o-identification'),

                                TextInput::make('dias_limite_rpp')
                                    ->label('Días Límite para Inscripción RPP')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix(' días naturales')
                                    ->placeholder('70')
                                    ->suffixIcon('heroicon-o-calendar-days'),

                                TextInput::make('dias_alerta_previa')
                                    ->label('Días de Alerta Previa a Vencimiento')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->default(15)
                                    ->suffix(' días naturales')
                                    ->placeholder('15')
                                    ->suffixIcon('heroicon-o-bell-alert'),

                                Toggle::make('activo')
                                    ->label('Institución Habilitada')
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
                    ->label('Institución')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('tipo')
                    ->label('Tipo de Entidad')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'banco' => 'Banco Comercial',
                        'instituto_vivienda' => 'Instituto Vivienda',
                        'particular' => 'Particular',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'banco' => 'primary',
                        'instituto_vivienda' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('dias_limite_rpp')
                    ->label('Límite RPP')
                    ->numeric()
                    ->suffix(' días naturales')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('dias_alerta_previa')
                    ->label('Alerta Previa')
                    ->numeric()
                    ->suffix(' días naturales')
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'banco' => 'Banco Comercial',
                        'instituto_vivienda' => 'Instituto de Vivienda',
                        'particular' => 'Particular',
                    ])
                    ->placeholder('Todos'),

                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),

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
                'tipo',
                'dias_limite_rpp',
                'dias_alerta_previa',
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
            'index' => ManageInstitucionesCreditos::route('/'),
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

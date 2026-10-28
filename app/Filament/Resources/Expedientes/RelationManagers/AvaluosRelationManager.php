<?php

namespace App\Filament\Resources\Expedientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvaluosRelationManager extends RelationManager
{
    protected static string $relationship = 'avaluos';

    protected static ?string $title = 'Avalúos Comerciales y Catastrales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->schema([
                        Select::make('perito_id')
                            ->label('Perito Valuador Autorizado')
                            ->relationship('perito', 'nombre_completo')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona perito')
                            ->suffixIcon('heroicon-o-user'),

                        Select::make('estatus')
                            ->label('Estatus del Avalúo')
                            ->options([
                                'solicitado' => 'Solicitado al Perito',
                                'en_proceso' => 'En Inspección / Proceso',
                                'entregado' => 'Entregado con Dictamen',
                                'observado' => 'Observado con Correcciones',
                            ])
                            ->default('solicitado')
                            ->required()
                            ->suffixIcon('heroicon-o-arrow-path'),

                        DatePicker::make('fecha_solicitud')
                            ->label('Fecha de Solicitud')
                            ->required()
                            ->default(now())
                            ->placeholder('ej. 2026-10-27')
                            ->suffixIcon('heroicon-o-calendar'),

                        DatePicker::make('fecha_estimada_entrega')
                            ->label('Fecha Estimada de Entrega')
                            ->required()
                            ->placeholder('ej. 2026-11-10')
                            ->suffixIcon('heroicon-o-calendar-days'),

                        DatePicker::make('fecha_recepcion_real')
                            ->label('Fecha Recepción Real')
                            ->placeholder('ej. 2026-11-09')
                            ->suffixIcon('heroicon-o-check-badge'),

                        TextInput::make('valor_catastral')
                            ->label('Valor Catastral Dictaminado')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('ej. 1850000.00')
                            ->suffixIcon('heroicon-o-calculator'),

                        TextInput::make('valor_comercial')
                            ->label('Valor Comercial Dictaminado')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('ej. 2400000.00')
                            ->suffixIcon('heroicon-o-banknotes'),

                        TextInput::make('archivo_avaluo_url')
                            ->label('Ruta / Dictamen Escaneado PDF')
                            ->maxLength(255)
                            ->placeholder('ej. avaluos/2026/dictamen_exp0145.pdf')
                            ->suffixIcon('heroicon-o-paper-clip')
                            ->columnSpan(['default' => 1, 'sm' => 2]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['perito']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('estatus')
            ->columns([
                TextColumn::make('perito.nombre_completo')
                    ->label('Perito Valuador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'solicitado' => 'warning',
                        'en_proceso' => 'info',
                        'entregado' => 'success',
                        'observado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'solicitado' => 'Solicitado',
                        'en_proceso' => 'En Proceso',
                        'entregado' => 'Entregado',
                        'observado' => 'Observado',
                        default => $state,
                    }),

                TextColumn::make('fecha_estimada_entrega')
                    ->label('Fecha Promesa')
                    ->date('d/m/Y'),

                TextColumn::make('valor_catastral')
                    ->label('Valor Catastral')
                    ->money('MXN')
                    ->placeholder('-'),

                TextColumn::make('valor_comercial')
                    ->label('Valor Comercial')
                    ->money('MXN')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'solicitado' => 'Solicitado',
                        'en_proceso' => 'En Proceso',
                        'entregado' => 'Entregado',
                        'observado' => 'Observado',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Solicitar Avalúo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
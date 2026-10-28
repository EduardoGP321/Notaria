<?php

namespace App\Filament\Resources\Presupuestos\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DetallesRelationManager extends RelationManager
{
    protected static string $relationship = 'presupuestoDetalles';

    protected static ?string $title = 'Partidas Desglosadas del Presupuesto';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->schema([
                        Select::make('concepto_id')
                            ->label('Concepto Gubernamental / Honorario')
                            ->relationship('concepto', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona concepto (ej. ISABI, RPP, Avalúo)')
                            ->suffixIcon('heroicon-o-tag')
                            ->columnSpan(['default' => 1, 'sm' => 2]),

                        TextInput::make('monto_estimado')
                            ->label('Monto Estimado')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->placeholder('ej. 15000.00')
                            ->suffixIcon('heroicon-o-currency-dollar'),

                        TextInput::make('observaciones')
                            ->label('Observaciones de Partida')
                            ->maxLength(255)
                            ->placeholder('ej. Cálculo según tabla catastral municipal 2026')
                            ->suffixIcon('heroicon-o-chat-bubble-bottom-center-text')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['concepto']))
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->recordTitleAttribute('concepto.nombre')
            ->columns([
                TextColumn::make('concepto.nombre')
                    ->label('Concepto')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('concepto.categoria')
                    ->label('Categoría')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'impuesto' => 'Impuesto',
                        'derecho' => 'Derecho',
                        'gasto_gestoria' => 'Gasto Gestoría',
                        'avaluo' => 'Avalúo',
                        'honorarios_notaria' => 'Honorarios',
                        default => $state,
                    }),

                TextColumn::make('monto_estimado')
                    ->label('Monto Estimado')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar Partida'),
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
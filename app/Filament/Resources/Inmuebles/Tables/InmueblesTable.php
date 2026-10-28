<?php

namespace App\Filament\Resources\Inmuebles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InmueblesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('clave_catastral')
                    ->label('Clave Catastral')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->placeholder('-'),

                TextColumn::make('cuenta_predial')
                    ->label('Cuenta Predial')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->sortable(),

                TextColumn::make('municipio.nombre')
                    ->label('Municipio')
                    ->sortable(),

                TextColumn::make('direccion_completa')
                    ->label('Ubicación')
                    ->wrap()
                    ->limit(35),

                TextColumn::make('superficie_terreno_m2')
                    ->label('Terreno')
                    ->numeric(2, '.', ',')
                    ->suffix(' m²')
                    ->placeholder('-'),

                TextColumn::make('superficie_construccion_m2')
                    ->label('Construcción')
                    ->numeric(2, '.', ',')
                    ->suffix(' m²')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('municipio_id')
                    ->label('Municipio')
                    ->relationship('municipio', 'nombre')
                    ->placeholder('Todos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
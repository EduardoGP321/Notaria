<?php

namespace App\Filament\Resources\LotesFolios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LotesFoliosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('numero_volumen_libro')
                    ->label('Volumen / Libro')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => "Libro {$state}"),

                TextColumn::make('folio_inicial')
                    ->label('Folio Inicial')
                    ->sortable(),

                TextColumn::make('folio_final')
                    ->label('Folio Final')
                    ->sortable(),

                TextColumn::make('folios_disponibles')
                    ->label('Folios Restantes')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 20 => 'danger',
                        $state <= 100 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                TextColumn::make('fecha_autorizacion')
                    ->label('Autorización')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
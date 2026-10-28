<?php

namespace App\Filament\Resources\CortesCajas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CortesCajasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('tipo_corte')
                    ->label('Tipo de Corte')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'diario' => 'Diario',
                        'semanal' => 'Semanal (Lunes)',
                        'extraordinario' => 'Extraordinario',
                        default => ucfirst($state ?? '-'),
                    })
                    ->colors([
                        'info' => 'diario',
                        'success' => 'semanal',
                        'warning' => 'extraordinario',
                    ]),

                TextColumn::make('fecha_inicio')
                    ->label('Desde')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Hasta')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('usuarioElabora.name')
                    ->label('Elaborado por')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('total_efectivo')
                    ->label('Efectivo')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('total_bancos')
                    ->label('Bancos / SPEI')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('monto_total')
                    ->label('Monto Consolidado')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('tipo_corte')
                    ->label('Tipo de Corte')
                    ->options([
                        'diario' => 'Corte Diario',
                        'semanal' => 'Corte Semanal (Lunes)',
                        'extraordinario' => 'Corte Extraordinario',
                    ]),

                SelectFilter::make('usuario_elabora_id')
                    ->label('Cajero / Responsable')
                    ->relationship('usuarioElabora', 'name'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
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
}
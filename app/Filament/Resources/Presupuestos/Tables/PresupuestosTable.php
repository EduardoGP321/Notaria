<?php

namespace App\Filament\Resources\Presupuestos\Tables;

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

class PresupuestosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('folio_presupuesto')
                    ->label('Folio')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('cliente.nombre_razon_social')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(35),

                TextColumn::make('tiposOperacion.nombre')
                    ->label('Operación')
                    ->sortable()
                    ->limit(25),

                TextColumn::make('valor_operacion')
                    ->label('Valor Operación')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('total_presupuesto')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aprobado_con_anticipo' => 'success',
                        'entregado' => 'info',
                        'cancelado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'aprobado_con_anticipo' => 'Aprobado c/Anticipo',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                        default => 'Borrador',
                    }),

                TextColumn::make('abogado.name')
                    ->label('Abogado')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('municipio.nombre')
                    ->label('Municipio')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha Elaboración')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('estatus')
                    ->label('Estatus')
                    ->options([
                        'borrador' => 'Borrador',
                        'entregado' => 'Entregado al Cliente',
                        'aprobado_con_anticipo' => 'Aprobado con Anticipo',
                        'cancelado' => 'Cancelado',
                    ])
                    ->placeholder('Todos'),

                SelectFilter::make('abogado_id')
                    ->label('Abogado')
                    ->relationship('abogado', 'name')
                    ->placeholder('Todos los abogados'),

                SelectFilter::make('tipo_operacion_id')
                    ->label('Tipo de Operación')
                    ->relationship('tiposOperacion', 'nombre')
                    ->placeholder('Todas las operaciones'),

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

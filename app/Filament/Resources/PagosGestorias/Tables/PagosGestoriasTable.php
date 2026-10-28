<?php

namespace App\Filament\Resources\PagosGestorias\Tables;

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

class PagosGestoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('linea_captura_referencia')
                    ->label('Línea de Captura')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->sortable(),

                TextColumn::make('concepto.nombre')
                    ->label('Concepto')
                    ->limit(25),

                TextColumn::make('monto_solicitado')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('estatus_pago')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pendiente' => 'danger',
                        'recurso_solicitado' => 'warning',
                        'entregado_a_gestor' => 'info',
                        'pagado_esperando_factura' => 'primary',
                        'factura_con_secretaria' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pendiente' => 'Pendiente',
                        'recurso_solicitado' => 'Recurso Solicitado',
                        'entregado_a_gestor' => 'Con Gestor',
                        'pagado_esperando_factura' => 'Pagado s/Factura',
                        'factura_con_secretaria' => 'Factura Recibida',
                        default => $state,
                    }),

                TextColumn::make('gestor.name')
                    ->label('Gestor')
                    ->placeholder('Sin gestor'),

                TextColumn::make('fecha_solicitud_recurso')
                    ->label('Solicitud')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_factura_secretaria')
                    ->label('Factura Lista')
                    ->date('d/m/Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estatus_pago')
                    ->label('Estatus del Pago')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'recurso_solicitado' => 'Recurso Solicitado',
                        'entregado_a_gestor' => 'Entregado a Gestor',
                        'pagado_esperando_factura' => 'Pagado esperando Factura',
                        'factura_con_secretaria' => 'Factura con Secretaria',
                    ]),

                SelectFilter::make('gestor_id')
                    ->label('Gestor Asignado')
                    ->relationship('gestor', 'name')
                    ->placeholder('Todos los gestores'),

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
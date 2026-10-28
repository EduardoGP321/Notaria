<?php

namespace App\Filament\Resources\RecibosIngresos\Tables;

use App\Models\RecibosIngreso;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RecibosIngresosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('folio_recibo_impreso')
                    ->label('Folio Recibo')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->sortable(),

                TextColumn::make('cliente.nombre_razon_social')
                    ->label('Cliente')
                    ->wrap()
                    ->limit(30),

                TextColumn::make('importe')
                    ->label('Importe')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('forma_pago')
                    ->label('Forma Pago')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'SPEI / Transf.',
                        'cheque' => 'Cheque',
                        'tarjeta' => 'Tarjeta',
                        default => $state,
                    }),

                TextColumn::make('fecha_pago')
                    ->label('Fecha Cobro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                IconColumn::make('impreso_en_foliado')
                    ->label('Foliado')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('requiere_cfdi')
                    ->label('CFDI')
                    ->boolean(),

                TextColumn::make('usuarioCobro.name')
                    ->label('Cajero')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('forma_pago')
                    ->label('Forma de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'cheque' => 'Cheque',
                        'tarjeta' => 'Tarjeta',
                    ]),

                TernaryFilter::make('requiere_cfdi')
                    ->label('Requiere CFDI')
                    ->placeholder('Todos'),

                TernaryFilter::make('impreso_en_foliado')
                    ->label('Impreso en Foliado')
                    ->placeholder('Todos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('imprimir_recibo_foliado')
                    ->label('Imprimir Folio')
                    ->Icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (RecibosIngreso $record): string => route('admin.recibos-ingresos.imprimir', $record), shouldOpenInNewTab: true)
                    ->after(function (RecibosIngreso $record) {
                        $record->update(['impreso_en_foliado' => true]);
                        Notification::make()
                            ->title('Impresión de Recibo Foliado')
                            ->body("El recibo {$record->folio_recibo_impreso} se abrió para impresión en papel membretado/foliado.")
                            ->success()
                            ->send();
                    }),
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

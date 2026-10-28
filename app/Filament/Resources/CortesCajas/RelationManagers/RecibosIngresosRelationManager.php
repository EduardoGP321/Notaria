<?php

namespace App\Filament\Resources\CortesCajas\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecibosIngresosRelationManager extends RelationManager
{
    protected static string $relationship = 'recibosIngresos';

    protected static ?string $title = 'Recibos de Ingreso Integrados al Corte';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('folio_recibo_impreso')
            ->columns([
                TextColumn::make('folio_recibo_impreso')
                    ->label('Folio Recibo')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('cliente.nombre_razon_social')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('nombre_concepto_recibo')
                    ->label('Concepto')
                    ->limit(40),

                TextColumn::make('forma_pago')
                    ->label('Forma de Pago')
                    ->badge(),

                TextColumn::make('importe')
                    ->label('Importe')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('fecha_pago')
                    ->label('Fecha Cobro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ]);
    }
}
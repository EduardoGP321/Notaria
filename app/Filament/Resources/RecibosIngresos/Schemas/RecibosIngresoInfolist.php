<?php

namespace App\Filament\Resources\RecibosIngresos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class RecibosIngresoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Detalle del Recibo de Ingreso')
                    ->tabs([
                        Tab::make('Cobro Notarial')
                            ->icon('heroicon-o-receipt-percent')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('folio_recibo_impreso')
                                                    ->label('Folio del Recibo')
                                                    ->weight('bold')
                                                    ->copyable(),

                                                TextEntry::make('expediente.numero_expediente')
                                                    ->label('Expediente'),

                                                TextEntry::make('cliente.nombre_razon_social')
                                                    ->label('Cliente Pagador'),

                                                TextEntry::make('importe')
                                                    ->label('Importe Cobrado')
                                                    ->money('MXN')
                                                    ->weight('bold'),

                                                TextEntry::make('forma_pago')
                                                    ->label('Forma de Pago')
                                                    ->badge(),

                                                TextEntry::make('banco_referencia')
                                                    ->label('Referencia / Banco')
                                                    ->placeholder('No registrada'),

                                                TextEntry::make('fecha_pago')
                                                    ->label('Fecha y Hora del Cobro')
                                                    ->dateTime('d/m/Y H:i'),

                                                TextEntry::make('usuarioCobro.name')
                                                    ->label('Colaborador Receptor'),

                                                TextEntry::make('nombre_concepto_recibo')
                                                    ->label('Concepto Detallado')
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Facturación y Caja')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                IconEntry::make('impreso_en_foliado')
                                                    ->label('Impreso en Papel Foliado')
                                                    ->boolean(),

                                                IconEntry::make('requiere_cfdi')
                                                    ->label('Requiere CFDI')
                                                    ->boolean(),

                                                TextEntry::make('uuid_factura_notaria')
                                                    ->label('UUID Fiscal (SAT)')
                                                    ->placeholder('Sin factura emitida')
                                                    ->copyable(),

                                                TextEntry::make('corte_caja_id')
                                                    ->label('Corte de Caja')
                                                    ->placeholder('Pendiente de corte'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Auditoría')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 3])
                                            ->schema([
                                                TextEntry::make('created_at')
                                                    ->label('Fecha de Creación')
                                                    ->dateTime('d/m/Y H:i'),

                                                TextEntry::make('updated_at')
                                                    ->label('Última Actualización')
                                                    ->dateTime('d/m/Y H:i'),

                                                TextEntry::make('deleted_at')
                                                    ->label('Fecha de Baja')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('Registro activo'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
<?php

namespace App\Filament\Resources\CortesCajas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CortesCajaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Detalle del Corte de Caja')
                    ->tabs([
                        Tab::make('Resumen y Saldos')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make('Cierre Contable de Caja')
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 4])
                                            ->schema([
                                                TextEntry::make('tipo_corte')
                                                    ->label('Modalidad')
                                                    ->badge()
                                                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                        'diario' => 'Corte Diario',
                                                        'semanal' => 'Semanal (Lunes)',
                                                        'extraordinario' => 'Extraordinario',
                                                        default => ucfirst($state ?? '-'),
                                                    }),

                                                TextEntry::make('usuarioElabora.name')
                                                    ->label('Responsable de Corte')
                                                    ->placeholder('-'),

                                                TextEntry::make('fecha_inicio')
                                                    ->label('Fecha Inicio')
                                                    ->date('d/m/Y'),

                                                TextEntry::make('fecha_fin')
                                                    ->label('Fecha Cierre')
                                                    ->date('d/m/Y'),
                                            ]),

                                        Grid::make(['default' => 1, 'sm' => 3])
                                            ->schema([
                                                TextEntry::make('total_efectivo')
                                                    ->label('Saldo Físico Efectivo')
                                                    ->money('MXN'),

                                                TextEntry::make('total_bancos')
                                                    ->label('Saldo Bancos / SPEI')
                                                    ->money('MXN'),

                                                TextEntry::make('monto_total')
                                                    ->label('Total Arqueado')
                                                    ->money('MXN')
                                                    ->weight('bold'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Recibos Vinculados')
                            ->icon('heroicon-o-receipt-percent')
                            ->schema([
                                Section::make('Recibos de Ingreso en este Periodo')
                                    ->schema([
                                        TextEntry::make('recibosIngresos.folio_recibo_impreso')
                                            ->label('Folios de Recibos Integrados')
                                            ->badge()
                                            ->separator(', ')
                                            ->placeholder('Sin recibos asignados en este corte'),
                                    ]),
                            ]),

                        Tab::make('Auditoría')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('Marcas de Tiempo')
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
                                                    ->label('Fecha de Eliminación')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('Registro Activo'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])->columns(1);
    }
}
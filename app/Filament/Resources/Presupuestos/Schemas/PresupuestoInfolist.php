<?php

namespace App\Filament\Resources\Presupuestos\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PresupuestoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Detalle del Presupuesto')
                    ->tabs([
                        Tab::make('Resumen General')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('folio_presupuesto')
                                                    ->label('Folio')
                                                    ->copyable(),

                                                TextEntry::make('estatus')
                                                    ->label('Estatus')
                                                    ->badge()
                                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                                        'aprobado_con_anticipo' => 'Aprobado con Anticipo',
                                                        'entregado' => 'Entregado al Cliente',
                                                        'cancelado' => 'Cancelado',
                                                        default => 'Borrador',
                                                    }),

                                                TextEntry::make('cliente.nombre_razon_social')
                                                    ->label('Cliente'),

                                                TextEntry::make('tiposOperacion.nombre')
                                                    ->label('Tipo de Operación'),

                                                TextEntry::make('municipio.nombre')
                                                    ->label('Municipio'),

                                                TextEntry::make('abogado.name')
                                                    ->label('Abogado Responsable'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Desglose Financiero')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('valor_operacion')
                                                    ->label('Valor de Operación')
                                                    ->money('MXN'),

                                                TextEntry::make('porcentaje_aplicado')
                                                    ->label('Factor Aplicado')
                                                    ->suffix('%'),

                                                TextEntry::make('subtotal_impuestos_der')
                                                    ->label('Impuestos y Derechos')
                                                    ->money('MXN'),

                                                TextEntry::make('honorarios_notariales')
                                                    ->label('Honorarios Notariales')
                                                    ->money('MXN'),

                                                TextEntry::make('iva_honorarios')
                                                    ->label('IVA de Honorarios')
                                                    ->money('MXN'),

                                                TextEntry::make('total_presupuesto')
                                                    ->label('Total del Presupuesto')
                                                    ->money('MXN')
                                                    ->weight('bold'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Partidas Detalladas')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                RepeatableEntry::make('presupuestoDetalles')
                                    ->label('Conceptos Notariales')
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 3])
                                            ->schema([
                                                TextEntry::make('concepto.nombre')
                                                    ->label('Concepto'),

                                                TextEntry::make('monto_estimado')
                                                    ->label('Monto Estimado')
                                                    ->money('MXN'),

                                                TextEntry::make('observaciones')
                                                    ->label('Observaciones')
                                                    ->placeholder('Sin observaciones'),
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
                                                    ->label('Última Modificación')
                                                    ->dateTime('d/m/Y H:i'),

                                                TextEntry::make('deleted_at')
                                                    ->label('Fecha de Baja')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('Registro activo'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])->columns(1);
    }
}

<?php

namespace App\Filament\Resources\PagosGestorias\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PagosGestoriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha del Pago de Gestoría')
                    ->tabs([
                        Tab::make('Detalle del Trámite')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('linea_captura_referencia')
                                                    ->label('Línea de Captura')
                                                    ->copyable()
                                                    ->weight('bold'),

                                                TextEntry::make('expediente.numero_expediente')
                                                    ->label('Expediente Notarial'),

                                                TextEntry::make('concepto.nombre')
                                                    ->label('Concepto Gubernamental'),

                                                TextEntry::make('monto_solicitado')
                                                    ->label('Monto Solicitado')
                                                    ->money('MXN')
                                                    ->weight('bold'),

                                                TextEntry::make('banco_pago')
                                                    ->label('Banco / Dependencia')
                                                    ->placeholder('No especificado'),

                                                TextEntry::make('estatus_pago')
                                                    ->label('Estatus Actual')
                                                    ->badge()
                                                    ->formatStateUsing(fn($state) => match ($state) {
                                                        'pendiente' => 'Pendiente de Autorización',
                                                        'recurso_solicitado' => 'Recurso Solicitado',
                                                        'entregado_a_gestor' => 'Entregado a Gestor',
                                                        'pagado_esperando_factura' => 'Pagado esperando Factura',
                                                        'factura_con_secretaria' => 'Factura con Secretaria',
                                                        default => $state,
                                                    }),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Trazabilidad de Fechas y Personal')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('adminCaja.name')
                                                    ->label('Administrador de Caja'),

                                                TextEntry::make('gestor.name')
                                                    ->label('Gestor de Campo')
                                                    ->placeholder('Sin gestor asignado'),

                                                TextEntry::make('secretariaRecibe.name')
                                                    ->label('Secretaria que Recibió Factura')
                                                    ->placeholder('Pendiente de entrega'),

                                                TextEntry::make('fecha_solicitud_recurso')
                                                    ->label('Fecha Solicitud')
                                                    ->date('d/m/Y'),

                                                TextEntry::make('fecha_entrega_gestor')
                                                    ->label('Fecha Entrega a Gestor')
                                                    ->date('d/m/Y')
                                                    ->placeholder('-'),

                                                TextEntry::make('fecha_pago_dependencia')
                                                    ->label('Fecha Pago en Ventanilla')
                                                    ->date('d/m/Y')
                                                    ->placeholder('-'),

                                                TextEntry::make('fecha_factura_secretaria')
                                                    ->label('Fecha Factura Entregada')
                                                    ->date('d/m/Y')
                                                    ->placeholder('-'),
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
            ])->columns(1);
    }
}

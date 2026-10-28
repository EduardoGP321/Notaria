<?php

namespace App\Filament\Resources\Expedientes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ExpedienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha del Expediente')
                    ->tabs([
                        Tab::make('Datos del Trámite')
                            ->icon('heroicon-o-folder')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('numero_expediente')
                                                    ->label('Número de Expediente')
                                                    ->copyable(),

                                                TextEntry::make('estatus_proceso')
                                                    ->label('Estatus Actual')
                                                    ->badge()
                                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                                        'iniciado' => 'Iniciado',
                                                        'aviso_preventivo' => 'Aviso Preventivo Presentado',
                                                        'en_avaluo' => 'En Avalúo',
                                                        'listo_para_firma' => 'Listo para Firma',
                                                        'firmado_en_folios' => 'Firmado en Folios',
                                                        'pagando_impuestos' => 'Pagando Impuestos',
                                                        'engargolado_para_rpp' => 'Engargolado para RPP',
                                                        'en_rpp' => 'En RPP',
                                                        'inscrito' => 'Inscrito en RPP',
                                                        'entregado' => 'Entregado al Cliente',
                                                        default => $state,
                                                    }),

                                                TextEntry::make('tiposOperacion.nombre')
                                                    ->label('Tipo de Operación'),

                                                TextEntry::make('institucionesCredito.nombre')
                                                    ->label('Institución de Crédito'),

                                                TextEntry::make('municipio.nombre')
                                                    ->label('Municipio de Radicación'),

                                                TextEntry::make('presupuesto.folio_presupuesto')
                                                    ->label('Presupuesto de Origen')
                                                    ->placeholder('Sin presupuesto vinculado'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Asignación y SLA')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('abogado.name')
                                                    ->label('Abogado Responsable'),

                                                TextEntry::make('secretaria.name')
                                                    ->label('Secretaria Asignada')
                                                    ->placeholder('Sin secretaria asignada'),

                                                IconEntry::make('requiere_factura_hon')
                                                    ->label('Requiere CFDI de Honorarios')
                                                    ->boolean(),

                                                TextEntry::make('fecha_apertura')
                                                    ->label('Fecha de Apertura')
                                                    ->date('d/m/Y'),

                                                TextEntry::make('fecha_limite_sla')
                                                    ->label('Fecha Límite Comprometida (SLA)')
                                                    ->date('d/m/Y')
                                                    ->placeholder('Sin fecha límite'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Observaciones')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('observaciones')
                                            ->label('Notas Notariales')
                                            ->placeholder('Sin observaciones registradas'),
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

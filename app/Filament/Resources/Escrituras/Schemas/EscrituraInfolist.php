<?php

namespace App\Filament\Resources\Escrituras\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EscrituraInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha de la Escritura')
                    ->tabs([
                        Tab::make('Protocolo Notarial')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('numero_escritura')
                                                    ->label('Número de Escritura')
                                                    ->weight('bold')
                                                    ->copyable(),

                                                TextEntry::make('expediente.numero_expediente')
                                                    ->label('Expediente Vinculado'),

                                                TextEntry::make('volumen')
                                                    ->label('Volumen / Libro')
                                                    ->formatStateUsing(fn($state) => "Vol. {$state}"),

                                                TextEntry::make('folio_inicio')
                                                    ->label('Folio Inicial'),

                                                TextEntry::make('folio_fin')
                                                    ->label('Folio Final'),

                                                TextEntry::make('cantidad_folios_usados')
                                                    ->label('Folios Consumidos'),

                                                TextEntry::make('forma_liquidacion_partes')
                                                    ->label('Forma de Liquidación')
                                                    ->formatStateUsing(fn($state) => match ($state) {
                                                        'efectivo' => 'Efectivo',
                                                        'cheque' => 'Cheque de Caja',
                                                        'transferencia' => 'Transferencia SPEI',
                                                        'mixto' => 'Liquidación Mixta',
                                                        default => 'No especificada',
                                                    }),

                                                TextEntry::make('fecha_firma')
                                                    ->label('Fecha de Otorgamiento')
                                                    ->date('d/m/Y'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('RPP y Entrega')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                IconEntry::make('firmas_completas')
                                                    ->label('Firmas Completas')
                                                    ->boolean(),

                                                IconEntry::make('engargolada_lista')
                                                    ->label('Engargolada para RPP')
                                                    ->boolean(),

                                                TextEntry::make('folio_real_rpp')
                                                    ->label('Folio Real RPP')
                                                    ->placeholder('No asignado aún')
                                                    ->copyable(),

                                                TextEntry::make('fecha_ingreso_rpp')
                                                    ->label('Ingreso a RPP')
                                                    ->date('d/m/Y')
                                                    ->placeholder('Pendiente'),

                                                TextEntry::make('fecha_inscripcion_rpp')
                                                    ->label('Inscripción Definitiva RPP')
                                                    ->date('d/m/Y')
                                                    ->placeholder('Pendiente'),

                                                TextEntry::make('fecha_entrega_cliente')
                                                    ->label('Fecha de Entrega al Cliente')
                                                    ->date('d/m/Y')
                                                    ->placeholder('No entregado'),
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

<?php

namespace App\Filament\Resources\LotesFolios\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class LotesFolioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha del Libro de Protocolo')
                    ->tabs([
                        Tab::make('Dotación de Folios')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('numero_volumen_libro')
                                                    ->label('Número de Volumen / Libro')
                                                    ->formatStateUsing(fn($state) => "Libro {$state}"),

                                                TextEntry::make('folio_inicial')
                                                    ->label('Folio Inicial'),

                                                TextEntry::make('folio_final')
                                                    ->label('Folio Final'),

                                                TextEntry::make('folios_disponibles')
                                                    ->label('Folios Disponibles')
                                                    ->badge(),

                                                TextEntry::make('fecha_autorizacion')
                                                    ->label('Fecha de Autorización')
                                                    ->date('d/m/Y'),
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
                                                    ->label('Fecha de Alta')
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

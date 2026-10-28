<?php

namespace App\Filament\Resources\LotesFolios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LotesFolioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dotación y Registro del Libro Notarial')
                    ->description('Control de folios autorizados por la Dirección del Archivo General de Notarías.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('numero_volumen_libro')
                                    ->label('Número de Volumen / Libro')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->placeholder('ej. 142')
                                    ->suffixIcon('heroicon-o-book-open'),

                                TextInput::make('folio_inicial')
                                    ->label('Folio Inicial')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->placeholder('ej. 71001')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                TextInput::make('folio_final')
                                    ->label('Folio Final')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->placeholder('ej. 71500')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                TextInput::make('folios_disponibles')
                                    ->label('Folios Disponibles en Libro')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('ej. 500')
                                    ->suffixIcon('heroicon-o-calculator'),

                                DatePicker::make('fecha_autorizacion')
                                    ->label('Fecha de Autorización Estatal')
                                    ->required()
                                    ->default(now())
                                    ->placeholder('ej. 2026-01-15')
                                    ->suffixIcon('heroicon-o-calendar'),
                            ]),
                    ]),
            ])->columns(1);
    }
}

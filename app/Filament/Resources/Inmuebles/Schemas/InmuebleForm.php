<?php

namespace App\Filament\Resources\Inmuebles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InmuebleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ubicación y Claves Catastrales')
                    ->description('Datos oficiales de registro municipal y catastro del inmueble objeto del acto.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                Select::make('expediente_id')
                                    ->label('Expediente Notarial')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona expediente')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('municipio_id')
                                    ->label('Municipio')
                                    ->relationship('municipio', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona municipio')
                                    ->suffixIcon('heroicon-o-map-pin'),

                                TextInput::make('clave_catastral')
                                    ->label('Clave Catastral')
                                    ->maxLength(50)
                                    ->placeholder('ej. 04-012-004-001')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                TextInput::make('cuenta_predial')
                                    ->label('Cuenta Predial')
                                    ->maxLength(50)
                                    ->placeholder('ej. U-098765-2026')
                                    ->suffixIcon('heroicon-o-credit-card'),

                                Textarea::make('direccion_completa')
                                    ->label('Dirección Completa del Inmueble')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('ej. Calle 5 de Mayo No. 1204, Int. 3, Col. Centro, C.P. 72000, Puebla, Pue.')
                                    ->columnSpan(['default' => 1, 'sm' => 2]),
                            ]),
                    ]),

                Section::make('Superficies, Medidas y Colindancias')
                    ->description('Descripción perimetral del lote o finca según título de propiedad.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('superficie_terreno_m2')
                                    ->label('Superficie de Terreno')
                                    ->numeric()
                                    ->suffix('m²')
                                    ->placeholder('ej. 250.00')
                                    ->suffixIcon('heroicon-o-square-2-stack'),

                                TextInput::make('superficie_construccion_m2')
                                    ->label('Superficie de Construcción')
                                    ->numeric()
                                    ->suffix('m²')
                                    ->placeholder('ej. 185.50')
                                    ->suffixIcon('heroicon-o-home'),

                                Textarea::make('medidas_colindancias')
                                    ->label('Medidas y Colindancias Perimetrales')
                                    ->rows(3)
                                    ->placeholder('ej. Al Norte 10.00 m con lote 5; al Sur 10.00 m con calle; al Oriente 25.00 m con lote 4; al Poniente 25.00 m con lote 6.')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Antecedentes de Propiedad')
                    ->description('Datos registrales del título de propiedad previo.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('antecedente_escritura')
                                    ->label('Antecedente Escritura / Título')
                                    ->maxLength(100)
                                    ->placeholder('ej. Escritura No. 34,120 ante Notaría 12 de Puebla')
                                    ->suffixIcon('heroicon-o-document-duplicate'),

                                TextInput::make('folio_real_antecedente')
                                    ->label('Folio Real / Tomo Antecedente')
                                    ->maxLength(80)
                                    ->placeholder('ej. 0293847-1')
                                    ->suffixIcon('heroicon-o-bookmark'),
                            ]),
                    ]),
            ])->columns(1);
    }
}

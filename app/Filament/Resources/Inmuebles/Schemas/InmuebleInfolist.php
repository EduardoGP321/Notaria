<?php

namespace App\Filament\Resources\Inmuebles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class InmuebleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha del Inmueble')
                    ->tabs([
                        Tab::make('Ubicación y Catastro')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('clave_catastral')
                                                    ->label('Clave Catastral')
                                                    ->copyable()
                                                    ->weight('bold'),

                                                TextEntry::make('cuenta_predial')
                                                    ->label('Cuenta Predial')
                                                    ->copyable(),

                                                TextEntry::make('municipio.nombre')
                                                    ->label('Municipio'),

                                                TextEntry::make('expediente.numero_expediente')
                                                    ->label('Expediente'),

                                                TextEntry::make('direccion_completa')
                                                    ->label('Dirección Completa')
                                                    ->columnSpan(['default' => 1, 'sm' => 2]),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Superficies y Perímetro')
                            ->icon('heroicon-o-arrows-pointing-out')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                TextEntry::make('superficie_terreno_m2')
                                                    ->label('Superficie de Terreno')
                                                    ->numeric(2, '.', ',')
                                                    ->suffix(' m²')
                                                    ->placeholder('No registrada'),

                                                TextEntry::make('superficie_construccion_m2')
                                                    ->label('Superficie de Construcción')
                                                    ->numeric(2, '.', ',')
                                                    ->suffix(' m²')
                                                    ->placeholder('No registrada'),

                                                TextEntry::make('medidas_colindancias')
                                                    ->label('Medidas y Colindancias')
                                                    ->placeholder('No registradas')
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Antecedentes')
                            ->icon('heroicon-o-document-duplicate')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                TextEntry::make('antecedente_escritura')
                                                    ->label('Antecedente Escritura')
                                                    ->placeholder('Sin antecedente'),

                                                TextEntry::make('folio_real_antecedente')
                                                    ->label('Folio Real Antecedente')
                                                    ->copyable()
                                                    ->placeholder('Sin folio real antecedente'),
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

<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ClienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha del Cliente')
                    ->tabs([
                        Tab::make('Identidad Legal')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('tipo_persona')
                                                    ->label('Tipo de Persona')
                                                    ->badge()
                                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                                        'moral' => 'Persona Moral',
                                                        default => 'Persona Física',
                                                    }),

                                                TextEntry::make('nombre_razon_social')
                                                    ->label('Nombre o Razón Social')
                                                    ->columnSpan(['default' => 1, 'sm' => 2]),

                                                TextEntry::make('rfc')
                                                    ->label('RFC')
                                                    ->copyable()
                                                    ->placeholder('No registrado'),

                                                TextEntry::make('curp')
                                                    ->label('CURP')
                                                    ->copyable()
                                                    ->placeholder('No registrado'),

                                                TextEntry::make('estado_civil')
                                                    ->label('Estado Civil')
                                                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                        'soltero' => 'Soltero(a)',
                                                        'casado_sociedad_conyugal' => 'Casado(a) - Sociedad Conyugal',
                                                        'casado_separacion_bienes' => 'Casado(a) - Separación de Bienes',
                                                        'divorciado' => 'Divorciado(a)',
                                                        'viudo' => 'Viudo(a)',
                                                        default => 'No especificado',
                                                    }),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Contacto y Localización')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                TextEntry::make('telefono')
                                                    ->label('Teléfono Directo')
                                                    ->copyable(),

                                                TextEntry::make('email')
                                                    ->label('Correo Electrónico')
                                                    ->copyable()
                                                    ->placeholder('Sin correo registrado'),

                                                TextEntry::make('domicilio_fiscal')
                                                    ->label('Domicilio Fiscal')
                                                    ->placeholder('Sin domicilio fiscal registrado')
                                                    ->columnSpanFull(),
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
                                                    ->label('Fecha de Registro')
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

<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación de la Persona')
                    ->description('Datos constitutivos o de identidad oficial del cliente o compareciente.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                Select::make('tipo_persona')
                                    ->label('Tipo de Persona')
                                    ->options([
                                        'fisica' => 'Persona Física',
                                        'moral' => 'Persona Moral',
                                    ])
                                    ->default('fisica')
                                    ->required()
                                    ->live()
                                    ->suffixIcon('heroicon-o-identification'),

                                TextInput::make('nombre_razon_social')
                                    ->label('Nombre Completo o Razón Social')
                                    ->required()
                                    ->maxLength(200)
                                    ->placeholder('ej. Inmobiliaria Angelópolis S.A. de C.V. o Juan Carlos Pérez Soto')
                                    ->suffixIcon('heroicon-o-building-office-2')
                                    ->columnSpan(['default' => 1, 'sm' => 2]),

                                TextInput::make('rfc')
                                    ->label('RFC con Homoclave')
                                    ->maxLength(13)
                                    ->placeholder('ej. IAN120315AB1 o PESJ800101XY2')
                                    ->suffixIcon('heroicon-o-credit-card'),

                                TextInput::make('curp')
                                    ->label('CURP')
                                    ->maxLength(18)
                                    ->visible(fn ($get): bool => $get('tipo_persona') === 'fisica')
                                    ->placeholder('ej. PESJ800101HDFRNR02')
                                    ->suffixIcon('heroicon-o-finger-print'),

                                Select::make('estado_civil')
                                    ->label('Estado Civil')
                                    ->visible(fn ($get): bool => $get('tipo_persona') === 'fisica')
                                    ->options([
                                        'soltero' => 'Soltero(a)',
                                        'casado_sociedad_conyugal' => 'Casado(a) - Sociedad Conyugal',
                                        'casado_separacion_bienes' => 'Casado(a) - Separación de Bienes',
                                        'divorciado' => 'Divorciado(a)',
                                        'viudo' => 'Viudo(a)',
                                    ])
                                    ->placeholder('Selecciona estado civil')
                                    ->suffixIcon('heroicon-o-user'),
                            ]),
                    ]),

                Section::make('Contacto y Domicilio Fiscal')
                    ->description('Vías de comunicación y dirección legal para notificaciones y facturación.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('telefono')
                                    ->label('Teléfono de Contacto')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('ej. 2221234567')
                                    ->suffixIcon('heroicon-o-phone'),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->maxLength(120)
                                    ->placeholder('ej. contacto@cliente.com.mx')
                                    ->suffixIcon('heroicon-o-envelope'),

                                Textarea::make('domicilio_fiscal')
                                    ->label('Domicilio Fiscal Completo')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->placeholder('ej. Av. Juárez 2108, Col. La Paz, C.P. 72160, Puebla, Pue.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])->columns(1);
    }
}

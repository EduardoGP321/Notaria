<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Usuario')
                    ->description('Datos personales y de contacto del colaborador en la notaría.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre Completo')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ej. Lic. Roberto Morales Sánchez')
                                    ->suffixIcon('heroicon-o-user'),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->maxLength(120)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ej. rmorales@notariapuebla.mx')
                                    ->suffixIcon('heroicon-o-envelope'),

                                TextInput::make('telefono')
                                    ->label('Teléfono Directo')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('ej. 2221234567')
                                    ->suffixIcon('heroicon-o-phone'),

                                TextInput::make('iniciales')
                                    ->label('Iniciales Operativas')
                                    ->maxLength(10)
                                    ->placeholder('ej. RMS')
                                    ->suffixIcon('heroicon-o-identification'),

                                Select::make('puestos')
                                    ->label('Puestos Asignados')
                                    ->relationship('puestos', 'nombre')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->placeholder('Selecciona uno o más puestos')
                                    ->suffixIcon('heroicon-o-briefcase'),

                                Toggle::make('activo')
                                    ->label('Usuario Activo')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),

                Section::make('Seguridad de la Cuenta')
                    ->description('Credenciales de acceso al sistema notarial.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('password')
                                    ->label('Contraseña')
                                    ->password()
                                    ->revealable()
                                    ->required(fn(string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->placeholder('ej. Min. 8 caracteres')
                                    ->suffixIcon('heroicon-o-lock-closed'),
                            ]),
                    ]),
            ])->columns(1);
    }
}

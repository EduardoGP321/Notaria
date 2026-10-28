<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Detalle del Usuario')
                    ->tabs([
                        Tab::make('Información General')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                                            ->schema([
                                                TextEntry::make('name')
                                                    ->label('Nombre Completo'),

                                                TextEntry::make('email')
                                                    ->label('Correo Electrónico')
                                                    ->copyable(),

                                                TextEntry::make('telefono')
                                                    ->label('Teléfono')
                                                    ->placeholder('-'),

                                                TextEntry::make('iniciales')
                                                    ->label('Iniciales')
                                                    ->badge()
                                                    ->placeholder('-'),

                                                IconEntry::make('activo')
                                                    ->label('Activo')
                                                    ->boolean(),

                                                TextEntry::make('email_verified_at')
                                                    ->label('Verificación de Correo')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('No verificado'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Puestos y Asignaciones')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('puestos.nombre')
                                            ->label('Puestos Asignados')
                                            ->badge()
                                            ->separator(', ')
                                            ->placeholder('Sin puestos asignados'),
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
                                                    ->label('Creado')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('-'),

                                                TextEntry::make('updated_at')
                                                    ->label('Última Actualización')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('-'),

                                                TextEntry::make('deleted_at')
                                                    ->label('Eliminado')
                                                    ->dateTime('d/m/Y H:i')
                                                    ->placeholder('Registro activo'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])->columns(1);
    }
}

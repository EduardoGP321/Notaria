<?php

namespace App\Filament\Resources\CortesCajas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CortesCajaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Periodo y Modalidad del Arqueo')
                    ->description('Cierre y conciliación periódica de cobros y valores recibidos en notaría.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('tipo_corte')
                                    ->label('Modalidad del Corte')
                                    ->options([
                                        'diario' => 'Corte Diario (Cierre ordinario de operaciones)',
                                        'semanal' => 'Corte Semanal (Arqueo consolidado de lunes)',
                                        'extraordinario' => 'Corte Extraordinario / Auditoría Interna',
                                    ])
                                    ->default('diario')
                                    ->required()
                                    ->suffixIcon('heroicon-o-clock'),

                                Select::make('usuario_elabora_id')
                                    ->label('Responsable de Caja que Elabora')
                                    ->relationship('usuarioElabora', 'name')
                                    ->default(auth()->id())
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->suffixIcon('heroicon-o-user'),

                                DatePicker::make('fecha_inicio')
                                    ->label('Fecha Inicial del Arqueo')
                                    ->required()
                                    ->default(now())
                                    ->suffixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_fin')
                                    ->label('Fecha Final del Arqueo')
                                    ->required()
                                    ->default(now())
                                    ->suffixIcon('heroicon-o-calendar'),
                            ]),
                    ]),

                Section::make('Conciliación Monetaria de Ingresos')
                    ->description('Desglose de numerario en caja fuerte, terminal bancaria y transferencias.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 3])
                            ->schema([
                                TextInput::make('total_efectivo')
                                    ->label('Total en Efectivo (Físico)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->required()
                                    ->default(0)
                                    ->placeholder('ej. 15450.00')
                                    ->suffixIcon('heroicon-o-banknotes'),

                                TextInput::make('total_bancos')
                                    ->label('Total Bancos / Terminal / SPEI')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->required()
                                    ->default(0)
                                    ->placeholder('ej. 89200.00')
                                    ->suffixIcon('heroicon-o-credit-card'),

                                TextInput::make('monto_total')
                                    ->label('Monto Consolidado Global')
                                    ->numeric()
                                    ->prefix('$')
                                    ->suffix('MXN')
                                    ->required()
                                    ->default(0)
                                    ->placeholder('ej. 104650.00')
                                    ->suffixIcon('heroicon-o-currency-dollar'),
                            ]),
                    ]),
            ])->columns(1);
    }
}
<?php

namespace App\Filament\Resources\Presupuestos\Schemas;

use App\Models\Municipio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PresupuestoForm
{
    public static function recalcularTotales(Get $get, Set $set): void
    {
        $valorOperacion = (float) ($get('valor_operacion') ?? 0);
        $porcentajeAplicado = (float) ($get('porcentaje_aplicado') ?? 0);
        $honorarios = (float) ($get('honorarios_notariales') ?? 0);

        // subtotal_impuestos_der = valor_operacion x (porcentaje aplicado / 100)
        $subtotalImpuestos = round($valorOperacion * ($porcentajeAplicado / 100), 2);
        $set('subtotal_impuestos_der', number_format($subtotalImpuestos, 2, '.', ''));

        // IVA de honorarios (16%)
        $iva = round($honorarios * 0.16, 2);
        $set('iva_honorarios', number_format($iva, 2, '.', ''));

        // total_presupuesto = subtotal_impuestos_der + honorarios_notariales + iva_honorarios
        $total = round($subtotalImpuestos + $honorarios + $iva, 2);
        $set('total_presupuesto', number_format($total, 2, '.', ''));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación y Asignación')
                    ->description('Datos de cabecera del presupuesto y partes intervinientes.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('folio_presupuesto')
                                    ->label('Folio del Presupuesto')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(30)
                                    ->placeholder('ej. PRES-2026-0042')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('cliente_id')
                                    ->label('Cliente Solicitante')
                                    ->relationship('cliente', 'nombre_razon_social')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el cliente')
                                    ->suffixIcon('heroicon-o-user')
                                    ->columnSpan(['default' => 1, 'sm' => 2]),

                                Select::make('tipo_operacion_id')
                                    ->label('Tipo de Operación')
                                    ->relationship('tiposOperacion', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el acto notarial')
                                    ->suffixIcon('heroicon-o-document-text'),

                                Select::make('municipio_id')
                                    ->label('Municipio del Inmueble')
                                    ->relationship('municipio', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state) {
                                            $municipio = Municipio::find($state);
                                            if ($municipio) {
                                                $set('porcentaje_aplicado', (float) $municipio->porcentaje_isabi);
                                            }
                                        }
                                        self::recalcularTotales($get, $set);
                                    })
                                    ->placeholder('Selecciona el municipio')
                                    ->suffixIcon('heroicon-o-map-pin'),

                                Select::make('abogado_id')
                                    ->label('Abogado Responsable')
                                    ->relationship('abogado', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el abogado')
                                    ->suffixIcon('heroicon-o-academic-cap'),

                                Select::make('estatus')
                                    ->label('Estatus del Presupuesto')
                                    ->options([
                                        'borrador' => 'Borrador Inicial',
                                        'entregado' => 'Entregado al Cliente',
                                        'aprobado_con_anticipo' => 'Aprobado con Anticipo',
                                        'cancelado' => 'Cancelado',
                                    ])
                                    ->default('borrador')
                                    ->required()
                                    ->suffixIcon('heroicon-o-clock'),
                            ]),
                    ]),

                Section::make('Importes y Cálculo Financiero')
                    ->description('Montos base de la operación, cálculo de honorarios e impuestos estimados.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('valor_operacion')
                                    ->label('Valor de la Operación')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 3500000.00')
                                    ->suffixIcon('heroicon-o-banknotes')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcularTotales($get, $set)),

                                TextInput::make('porcentaje_aplicado')
                                    ->label('Tasa / Factor Aplicado')
                                    ->numeric()
                                    ->default(2.00)
                                    ->required()
                                    ->suffix('%')
                                    ->placeholder('ej. 2.00')
                                    ->suffixIcon('heroicon-o-variable')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcularTotales($get, $set)),

                                TextInput::make('subtotal_impuestos_der')
                                    ->label('Subtotal Impuestos y Derechos')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 70000.00')
                                    ->suffixIcon('heroicon-o-scale'),

                                TextInput::make('honorarios_notariales')
                                    ->label('Honorarios Notariales')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 35000.00')
                                    ->suffixIcon('heroicon-o-briefcase')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcularTotales($get, $set)),

                                TextInput::make('iva_honorarios')
                                    ->label('IVA de Honorarios (16%)')
                                    ->numeric()
                                    ->default(0.00)
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 5600.00')
                                    ->suffixIcon('heroicon-o-receipt-percent'),

                                TextInput::make('total_presupuesto')
                                    ->label('Total Presupuesto')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 110600.00')
                                    ->suffixIcon('heroicon-o-calculator'),
                            ]),
                    ]),

                Section::make('Desglose de Conceptos Notariales')
                    ->description('Detalle partida por partida de los derechos, impuestos, avalúos y gastos de gestoría.')
                    ->schema([
                        Repeater::make('presupuestoDetalles')
                            ->relationship('presupuestoDetalles')
                            ->schema([
                                Select::make('concepto_id')
                                    ->label('Concepto')
                                    ->relationship('concepto', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el concepto')
                                    ->suffixIcon('heroicon-o-tag')
                                    ->columnSpan(2),

                                TextInput::make('monto')
                                    ->label('Importe Partida')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->placeholder('ej. 4500.00')
                                    ->suffixIcon('heroicon-o-currency-dollar')
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => isset($state['concepto_id'])
                                ? 'Partida de detalle'
                                : 'Nueva Partida')
                            ->addActionLabel('Agregar Partida al Presupuesto'),
                    ]),
            ])->columns(1);
    }
}

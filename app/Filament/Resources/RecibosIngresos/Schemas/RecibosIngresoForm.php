<?php

namespace App\Filament\Resources\RecibosIngresos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RecibosIngresoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación del Cobro y Expediente')
                    ->description('Datos del recibo foliado y vinculación al cliente y expediente notarial.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('folio_recibo_impreso')
                                    ->label('Folio del Recibo Físico')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(30)
                                    ->placeholder('ej. REC-08954')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('expediente_id')
                                    ->label('Expediente Notarial')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona expediente')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('cliente_id')
                                    ->label('Cliente Pagador')
                                    ->relationship('cliente', 'nombre_razon_social')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona cliente')
                                    ->suffixIcon('heroicon-o-user'),

                                TextInput::make('nombre_concepto_recibo')
                                    ->label('Concepto Detallado del Cobro')
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('ej. Anticipo para pago de ISABI y derechos de inscripción RPP')
                                    ->suffixIcon('heroicon-o-document-text')
                                    ->columnSpan(['default' => 1, 'sm' => 2, 'lg' => 3]),
                            ]),
                    ]),

                Section::make('Monto y Forma de Liquidación')
                    ->description('Detalle financiero del cobro ingresado en caja.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('importe')
                                    ->label('Importe Cobrado')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 45000.00')
                                    ->suffixIcon('heroicon-o-banknotes'),

                                Select::make('forma_pago')
                                    ->label('Forma de Pago')
                                    ->options([
                                        'efectivo' => 'Efectivo',
                                        'transferencia' => 'Transferencia Bancaria SPEI',
                                        'cheque' => 'Cheque de Caja',
                                        'tarjeta' => 'Tarjeta de Débito / Crédito',
                                    ])
                                    ->required()
                                    ->suffixIcon('heroicon-o-credit-card'),

                                TextInput::make('banco_referencia')
                                    ->label('Banco / Número de Referencia')
                                    ->maxLength(100)
                                    ->placeholder('ej. BBVA Transf. 9847294 o Cheque 001248')
                                    ->suffixIcon('heroicon-o-building-library'),

                                Select::make('usuario_cobro_id')
                                    ->label('Colaborador de Caja que Cobró')
                                    ->relationship('usuarioCobro', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona cajero/colaborador')
                                    ->suffixIcon('heroicon-o-user'),

                                DateTimePicker::make('fecha_pago')
                                    ->label('Fecha y Hora del Cobro')
                                    ->required()
                                    ->default(now())
                                    ->suffixIcon('heroicon-o-clock'),

                                Select::make('corte_caja_id')
                                    ->label('Corte de Caja Vinculado')
                                    ->relationship('corteCaja', 'id')
                                    ->placeholder('Sin corte asignado aún')
                                    ->suffixIcon('heroicon-o-archive-box'),

                                Toggle::make('impreso_en_foliado')
                                    ->label('Impreso en Papel Foliado')
                                    ->default(false)
                                    ->inline(false),

                                Toggle::make('requiere_cfdi')
                                    ->label('Requiere CFDI')
                                    ->default(false)
                                    ->inline(false),

                                TextInput::make('uuid_factura_notaria')
                                    ->label('UUID de Factura Notaría (SAT)')
                                    ->maxLength(50)
                                    ->placeholder('ej. 4A8B9C10-D23E-4567-E890-123456789ABC')
                                    ->suffixIcon('heroicon-o-receipt-percent')
                                    ->columnSpan(['default' => 1, 'sm' => 2]),
                            ]),
                    ]),
            ])->columns(1);
    }
}

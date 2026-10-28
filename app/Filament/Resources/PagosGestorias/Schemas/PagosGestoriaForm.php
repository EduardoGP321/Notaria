<?php

namespace App\Filament\Resources\PagosGestorias\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PagosGestoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Solicitud de Recursos y Trámite Notarial')
                    ->description('Datos del expediente, concepto gubernamental y línea de captura para pago.')
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

                                Select::make('concepto_id')
                                    ->label('Concepto Gubernamental')
                                    ->relationship('concepto', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona concepto (ej. ISABI, RPP, Catastro)')
                                    ->suffixIcon('heroicon-o-tag'),

                                TextInput::make('linea_captura_referencia')
                                    ->label('Línea de Captura / Referencia')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('ej. 0126-7890-4561-2389')
                                    ->suffixIcon('heroicon-o-qr-code'),

                                TextInput::make('banco_pago')
                                    ->label('Banco / Dependencia de Pago')
                                    ->maxLength(80)
                                    ->placeholder('ej. Banorte Ventanilla o Recaudación Finanzas')
                                    ->suffixIcon('heroicon-o-building-library'),

                                TextInput::make('monto_solicitado')
                                    ->label('Monto Solicitado a Caja')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('ej. 18750.00')
                                    ->suffixIcon('heroicon-o-banknotes'),

                                Select::make('estatus_pago')
                                    ->label('Estatus del Trámite')
                                    ->options([
                                        'pendiente' => 'Pendiente de Autorización',
                                        'recurso_solicitado' => 'Recurso Solicitado a Caja',
                                        'entregado_a_gestor' => 'Entregado a Gestor de Campo',
                                        'pagado_esperando_factura' => 'Pagado (Esperando Factura)',
                                        'factura_con_secretaria' => 'Factura en Poder de Secretaria',
                                    ])
                                    ->default('pendiente')
                                    ->required()
                                    ->suffixIcon('heroicon-o-arrow-path'),
                            ]),
                    ]),

                Section::make('Asignación de Personal y Fechas de Flujo')
                    ->description('Trazabilidad de entrega de dinero, pago en ventanilla y recepción de comprobantes.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                Select::make('admin_caja_id')
                                    ->label('Administrador de Caja')
                                    ->relationship('adminCaja', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona cajero')
                                    ->suffixIcon('heroicon-o-user'),

                                Select::make('gestor_id')
                                    ->label('Gestor de Campo Asignado')
                                    ->relationship('gestor', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Selecciona gestor')
                                    ->suffixIcon('heroicon-o-briefcase'),

                                Select::make('secretaria_recibe_id')
                                    ->label('Secretaria que Recibe Factura')
                                    ->relationship('secretariaRecibe', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Selecciona secretaria')
                                    ->suffixIcon('heroicon-o-academic-cap'),

                                DatePicker::make('fecha_solicitud_recurso')
                                    ->label('Fecha de Solicitud')
                                    ->required()
                                    ->default(now())
                                    ->placeholder('ej. 2026-10-27')
                                    ->suffixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_entrega_gestor')
                                    ->label('Fecha Entrega a Gestor')
                                    ->placeholder('ej. 2026-10-28')
                                    ->suffixIcon('heroicon-o-calendar-days'),

                                DatePicker::make('fecha_pago_dependencia')
                                    ->label('Fecha Pago en Dependencia')
                                    ->placeholder('ej. 2026-10-29')
                                    ->suffixIcon('heroicon-o-calendar-days'),

                                DatePicker::make('fecha_factura_secretaria')
                                    ->label('Fecha Factura Entregada')
                                    ->placeholder('ej. 2026-10-30')
                                    ->suffixIcon('heroicon-o-check-badge'),

                                TextInput::make('comprobante_pago_url')
                                    ->label('Ruta / Comprobante de Pago')
                                    ->maxLength(255)
                                    ->placeholder('ej. gestoria/comprobantes/pago_isabi_exp0145.pdf')
                                    ->suffixIcon('heroicon-o-document-arrow-up'),

                                TextInput::make('factura_catastro_pdf_url')
                                    ->label('Ruta / Factura Oficial Catastro')
                                    ->maxLength(255)
                                    ->placeholder('ej. gestoria/facturas/factura_catastro_exp0145.pdf')
                                    ->suffixIcon('heroicon-o-receipt-percent'),
                            ]),
                    ]),
            ])->columns(1);
    }
}


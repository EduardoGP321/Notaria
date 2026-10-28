<?php

namespace App\Filament\Resources\Expedientes\Schemas;

use App\Models\InstitucionesCredito;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ExpedienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Carátula y Clasificación del Expediente')
                    ->description('Datos registrales del expediente, presupuesto base y tipo de acto notarial.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('numero_expediente')
                                    ->label('Número de Expediente')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(30)
                                    ->placeholder('ej. EXP-2026-0145')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('presupuesto_id')
                                    ->label('Presupuesto Aprobado')
                                    ->relationship('presupuesto', 'folio_presupuesto')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Selecciona presupuesto previo (opcional)')
                                    ->suffixIcon('heroicon-o-calculator'),

                                Select::make('tipo_operacion_id')
                                    ->label('Tipo de Operación')
                                    ->relationship('tiposOperacion', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el acto a formalizar')
                                    ->suffixIcon('heroicon-o-document-text'),

                                Select::make('institucion_credito_id')
                                    ->label('Institución de Crédito / Acreedor')
                                    ->relationship('institucionesCredito', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state && $fecha = $get('fecha_apertura')) {
                                            $banco = InstitucionesCredito::find($state);
                                            if ($banco && $banco->dias_limite_rpp) {
                                                $set('fecha_limite_sla', Carbon::parse($fecha)->addDays((int) $banco->dias_limite_rpp)->format('Y-m-d'));
                                            }
                                        }
                                    })
                                    ->placeholder('Selecciona banco o entidad')
                                    ->suffixIcon('heroicon-o-building-library'),

                                Select::make('municipio_id')
                                    ->label('Municipio de Radicación')
                                    ->relationship('municipio', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el municipio')
                                    ->suffixIcon('heroicon-o-map-pin'),

                                Select::make('estatus_proceso')
                                    ->label('Estatus del Trámite')
                                    ->options([
                                        'iniciado' => 'Iniciado',
                                        'aviso_preventivo' => 'Aviso Preventivo Presentado',
                                        'en_avaluo' => 'En Avalúo Comercial/Catastral',
                                        'listo_para_firma' => 'Listo para Firma',
                                        'firmado_en_folios' => 'Firmado en Folios Notariales',
                                        'pagando_impuestos' => 'Pagando Impuestos y Derechos',
                                        'engargolado_para_rpp' => 'Engargolado para RPP',
                                        'en_rpp' => 'En Trámite en RPP',
                                        'inscrito' => 'Inscrito en RPP',
                                        'entregado' => 'Entregado al Cliente',
                                    ])
                                    ->default('iniciado')
                                    ->required()
                                    ->suffixIcon('heroicon-o-arrow-path'),
                            ]),
                    ]),

                Section::make('Asignación de Personal y Tiempos de Entrega (SLA)')
                    ->description('Responsables notariales y fechas límite para cumplimiento del servicio.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                Select::make('abogado_id')
                                    ->label('Abogado Dictaminador')
                                    ->relationship('abogado', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el abogado')
                                    ->suffixIcon('heroicon-o-academic-cap'),

                                Select::make('secretaria_id')
                                    ->label('Secretaria de Seguimiento')
                                    ->relationship('secretaria', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Selecciona la secretaria')
                                    ->suffixIcon('heroicon-o-user'),

                                Toggle::make('requiere_factura_hon')
                                    ->label('Requiere Factura Fiscal de Honorarios')
                                    ->default(false)
                                    ->inline(false),

                                DatePicker::make('fecha_apertura')
                                    ->label('Fecha de Apertura')
                                    ->required()
                                    ->default(now())
                                    ->placeholder('ej. 2026-10-27')
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state && $bancoId = $get('institucion_credito_id')) {
                                            $banco = InstitucionesCredito::find($bancoId);
                                            if ($banco && $banco->dias_limite_rpp) {
                                                $set('fecha_limite_sla', Carbon::parse($state)->addDays((int) $banco->dias_limite_rpp)->format('Y-m-d'));
                                            }
                                        }
                                    }),

                                DatePicker::make('fecha_limite_sla')
                                    ->label('Fecha Límite Comprometida (SLA)')
                                    ->placeholder('ej. 2026-11-25')
                                    ->suffixIcon('heroicon-o-calendar-days'),
                            ]),

                        Textarea::make('observaciones')
                            ->label('Observaciones Notariales')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('ej. Operación vinculada a crédito Cofinavit; se requiere solicitar certificado de no gravamen urgente al RPP.')
                            ->columnSpanFull(),
                    ]),
            ])->columns(1);
    }
}

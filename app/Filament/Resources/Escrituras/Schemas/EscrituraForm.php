<?php

namespace App\Filament\Resources\Escrituras\Schemas;

use App\Models\Avaluo;
use App\Models\PagosGestoria;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EscrituraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Protocolo y Asignación de Folios')
                    ->description('Datos del instrumento notarial, folios oficiales y libro/volumen.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                TextInput::make('numero_escritura')
                                    ->label('Número de Escritura')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ej. 45280')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                Select::make('expediente_id')
                                    ->label('Expediente Notarial')
                                    ->relationship('expediente', 'numero_expediente')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->placeholder('Selecciona expediente notarial')
                                    ->suffixIcon('heroicon-o-folder'),

                                Select::make('lote_folio_id')
                                    ->label('Lote de Folios Asignado')
                                    ->relationship('loteFolio', 'numero_volumen_libro')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Selecciona el libro/lote')
                                    ->suffixIcon('heroicon-o-archive-box'),

                                TextInput::make('volumen')
                                    ->label('Volumen / Tomo')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('ej. 142')
                                    ->suffixIcon('heroicon-o-book-open'),

                                TextInput::make('folio_inicio')
                                    ->label('Folio Inicial')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('ej. 71001')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                TextInput::make('folio_fin')
                                    ->label('Folio Final')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('ej. 71012')
                                    ->suffixIcon('heroicon-o-hashtag'),

                                TextInput::make('cantidad_folios_usados')
                                    ->label('Total de Folios Utilizados')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('ej. 12')
                                    ->suffixIcon('heroicon-o-document-duplicate'),

                                Select::make('forma_liquidacion_partes')
                                    ->label('Forma de Liquidación')
                                    ->options([
                                        'efectivo' => 'Efectivo',
                                        'cheque' => 'Cheque de Caja',
                                        'transferencia' => 'Transferencia SPEI',
                                        'mixto' => 'Liquidación Mixta',
                                    ])
                                    ->placeholder('Selecciona forma de pago')
                                    ->suffixIcon('heroicon-o-banknotes'),

                                DatePicker::make('fecha_firma')
                                    ->label('Fecha de Otorgamiento / Firma')
                                    ->required()
                                    ->default(now())
                                    ->placeholder('ej. 2026-10-27')
                                    ->suffixIcon('heroicon-o-pencil-square'),
                            ]),
                    ]),

                Section::make('Seguimiento en RPP y Conclusión')
                    ->description('Estado de firmas, presentación registral y entrega al compareciente.')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'lg' => 3])
                            ->schema([
                                Toggle::make('firmas_completas')
                                    ->label('Firmas Completas')
                                    ->default(false)
                                    ->inline(false),

                                Toggle::make('engargolada_lista')
                                    ->label('Testimonio Engargolado para RPP')
                                    ->default(false)
                                    ->inline(false)
                                    ->live()
                                    ->beforeStateUpdated(function (Get $get, Set $set, ?bool $state, $component) {
                                        if ($state) {
                                            $expedienteId = $get('expediente_id');
                                            if (! $expedienteId) {
                                                Notification::make()
                                                    ->title('Candado Notarial de Engargolado')
                                                    ->body('Debes seleccionar primero un expediente notarial.')
                                                    ->danger()
                                                    ->persistent()
                                                    ->send();
                                                $component->state(false);
                                                return;
                                            }

                                            $errores = [];

                                            // 1. Verificar pagos de gestoría
                                            $pagosPendientes = PagosGestoria::query()
                                                ->where('expediente_id', $expedienteId)
                                                ->where('estatus_pago', '!=', 'factura_con_secretaria')
                                                ->with('concepto:id,nombre')
                                                ->get();

                                            if ($pagosPendientes->isNotEmpty()) {
                                                $conceptos = $pagosPendientes->map(fn ($p) => $p->concepto?->nombre ?? "Pago #{$p->id}")->implode(', ');
                                                $errores[] = "Pagos sin factura en secretaría ({$pagosPendientes->count()}): {$conceptos}.";
                                            }

                                            // 2. Verificar avalúos
                                            $avaluosPendientes = Avaluo::query()
                                                ->where('expediente_id', $expedienteId)
                                                ->whereNotIn('estatus', ['concluido', 'entregado'])
                                                ->get();

                                            if ($avaluosPendientes->isNotEmpty()) {
                                                $errores[] = "Avalúo del expediente no se encuentra concluido/entregado ({$avaluosPendientes->count()}).";
                                            }

                                            if (! empty($errores)) {
                                                Notification::make()
                                                    ->title('Candado de Engargolado Bloqueado')
                                                    ->body('No es posible armar ni engargolar para RPP. Motivos pendientes: ' . implode(' | ', $errores))
                                                    ->danger()
                                                    ->persistent()
                                                    ->send();

                                                $component->state(false);
                                            }
                                        }
                                    })
                                    ->rules([
                                        fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                            if ($value) {
                                                $expedienteId = $get('expediente_id');
                                                if ($expedienteId) {
                                                    $pagosPendientes = PagosGestoria::where('expediente_id', $expedienteId)
                                                        ->where('estatus_pago', '!=', 'factura_con_secretaria')
                                                        ->count();

                                                    if ($pagosPendientes > 0) {
                                                        $fail("No se puede marcar como engargolada lista porque existen {$pagosPendientes} pagos de gestoría sin factura con secretaría.");
                                                    }

                                                    $avaluosPendientes = Avaluo::where('expediente_id', $expedienteId)
                                                        ->whereNotIn('estatus', ['concluido', 'entregado'])
                                                        ->count();

                                                    if ($avaluosPendientes > 0) {
                                                        $fail("No se puede marcar como engargolada lista porque el avalúo del expediente no está concluido/entregado.");
                                                    }
                                                }
                                            }
                                        },
                                    ]),

                                TextInput::make('folio_real_rpp')
                                    ->label('Folio Real RPP')
                                    ->maxLength(80)
                                    ->placeholder('ej. 0184729-1')
                                    ->suffixIcon('heroicon-o-document-check'),

                                DatePicker::make('fecha_ingreso_rpp')
                                    ->label('Fecha de Ingreso a RPP')
                                    ->placeholder('ej. 2026-11-10')
                                    ->suffixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_inscripcion_rpp')
                                    ->label('Fecha de Inscripción Definitiva')
                                    ->placeholder('ej. 2026-11-28')
                                    ->suffixIcon('heroicon-o-calendar-days'),

                                DatePicker::make('fecha_entrega_cliente')
                                    ->label('Fecha de Entrega al Cliente')
                                    ->placeholder('ej. 2026-12-05')
                                    ->suffixIcon('heroicon-o-check-badge'),

                                TextInput::make('copia_archivo_url')
                                    ->label('Ruta / Enlace al Testimonio Escaneado')
                                    ->maxLength(255)
                                    ->placeholder('ej. escrituras/2026/escritura_45280_firmada.pdf')
                                    ->suffixIcon('heroicon-o-link')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])->columns(1);
    }
}

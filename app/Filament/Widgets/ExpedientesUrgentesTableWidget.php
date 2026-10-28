<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Expedientes\ExpedienteResource;
use App\Models\Expediente;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ExpedientesUrgentesTableWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Expedientes Prioritarios y Monitoreo de Plazos RPP (Top 5)')
            ->description('Trámites activos ordenados por proximidad a la fecha límite SLA de registro')
            ->deferLoading()
            ->paginated(false)
            ->query(
                Expediente::query()
                    ->whereNotIn('estatus_proceso', ['en_rpp', 'inscrito', 'entregado'])
                    ->with([
                        'tiposOperacion:id,nombre',
                        'institucionesCredito:id,nombre,dias_alerta_previa',
                        'abogado:id,name',
                        'secretaria:id,name',
                        'expedienteComparecientes.cliente:id,nombre_razon_social',
                        'presupuesto.cliente:id,nombre_razon_social',
                    ])
                    ->orderByRaw('CASE WHEN fecha_limite_sla IS NULL THEN 1 ELSE 0 END, fecha_limite_sla ASC')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('numero_expediente')
                    ->label('Expediente')
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('cliente_principal')
                    ->label('Cliente Principal')
                    ->state(fn (Expediente $record): string =>
                        $record->expedienteComparecientes->first()?->cliente?->nombre_razon_social
                        ?? $record->presupuesto?->cliente?->nombre_razon_social
                        ?? 'Sin compareciente registrado'
                    )
                    ->limit(28)
                    ->placeholder('-'),

                TextColumn::make('tiposOperacion.nombre')
                    ->label('Acto Notarial')
                    ->limit(24),

                TextColumn::make('institucionesCredito.nombre')
                    ->label('Institución / Banco')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Santander' => 'danger',
                        'Infonavit' => 'warning',
                        'Particular (Sin Crédito)' => 'gray',
                        default => 'info',
                    }),

                TextColumn::make('responsables')
                    ->label('Abogado / Secretaria')
                    ->state(fn (Expediente $record): string =>
                        ($record->abogado?->name ?? 'Sin abogado') . ' / ' . ($record->secretaria?->name ?? 'Sin secretaria')
                    )
                    ->limit(28),

                TextColumn::make('estatus_proceso')
                    ->label('Estatus Trámite')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'iniciado' => 'Iniciado',
                        'aviso_preventivo' => 'Aviso Preventivo',
                        'en_avaluo' => 'En Avalúo',
                        'listo_para_firma' => 'Listo Firma',
                        'firmado_en_folios' => 'Firmado',
                        'pagando_impuestos' => 'Pagando Imp.',
                        'engargolado_para_rpp' => 'Engargolado',
                        default => $state,
                    }),

                TextColumn::make('fecha_limite_sla')
                    ->label('Límite RPP')
                    ->date('d/m/Y')
                    ->badge()
                    ->color(function (Expediente $record): string {
                        if (! $record->fecha_limite_sla) {
                            return 'gray';
                        }
                        $now = now()->startOfDay();
                        $limite = $record->fecha_limite_sla->copy()->startOfDay();

                        if ($now->gt($limite)) {
                            return 'danger';
                        }

                        $diasRestantes = $now->diffInDays($limite, false);
                        $diasAlerta = (int) ($record->institucionesCredito?->dias_alerta_previa ?? 15);

                        if ($diasRestantes <= $diasAlerta) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->description(function (Expediente $record): ?string {
                        if (! $record->fecha_limite_sla) {
                            return 'Sin plazo bancario';
                        }
                        $now = now()->startOfDay();
                        $limite = $record->fecha_limite_sla->copy()->startOfDay();

                        if ($now->gt($limite)) {
                            $vencido = $limite->diffInDays($now);
                            return "Vencido hace {$vencido} d";
                        }

                        $restantes = $now->diffInDays($limite, false);
                        return "{$restantes} d restantes";
                    })
                    ->placeholder('-'),
            ])
            ->recordActions([
                Action::make('ver_expediente')
                    ->label('Ver')
                    ->Icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Expediente $record): string => ExpedienteResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ]);
    }
}

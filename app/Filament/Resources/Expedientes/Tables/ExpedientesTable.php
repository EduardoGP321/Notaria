<?php

namespace App\Filament\Resources\Expedientes\Tables;

use App\Models\Expediente;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExpedientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('numero_expediente')
                    ->label('Número')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('tiposOperacion.nombre')
                    ->label('Operación')
                    ->sortable()
                    ->wrap()
                    ->limit(30),

                TextColumn::make('institucionesCredito.nombre')
                    ->label('Institución / Banco')
                    ->sortable()
                    ->limit(20),

                TextColumn::make('abogado.name')
                    ->label('Abogado')
                    ->sortable(),

                TextColumn::make('estatus_proceso')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'iniciado' => 'gray',
                        'aviso_preventivo' => 'warning',
                        'en_avaluo' => 'info',
                        'listo_para_firma' => 'primary',
                        'firmado_en_folios', 'inscrito', 'entregado' => 'success',
                        'pagando_impuestos' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'iniciado' => 'Iniciado',
                        'aviso_preventivo' => 'Aviso Prev.',
                        'en_avaluo' => 'En Avalúo',
                        'listo_para_firma' => 'Listo Firma',
                        'firmado_en_folios' => 'Firmado',
                        'pagando_impuestos' => 'Pagando Imp.',
                        'engargolado_para_rpp' => 'Engargolado',
                        'en_rpp' => 'En RPP',
                        'inscrito' => 'Inscrito',
                        'entregado' => 'Entregado',
                        default => $state,
                    }),

                TextColumn::make('fecha_apertura')
                    ->label('Apertura')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_limite_sla')
                    ->label('Límite SLA')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color(function (Expediente $record): string {
                        if (! $record->fecha_limite_sla) {
                            return 'gray';
                        }

                        if (in_array($record->estatus_proceso, ['en_rpp', 'inscrito', 'entregado'])) {
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
                            return null;
                        }

                        if (in_array($record->estatus_proceso, ['en_rpp', 'inscrito', 'entregado'])) {
                            return 'En tiempo (Atendido)';
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

                TextColumn::make('secretaria.name')
                    ->label('Secretaria')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('municipio.nombre')
                    ->label('Municipio')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estatus_proceso')
                    ->label('Estatus del Proceso')
                    ->options([
                        'iniciado' => 'Iniciado',
                        'aviso_preventivo' => 'Aviso Preventivo',
                        'en_avaluo' => 'En Avalúo',
                        'listo_para_firma' => 'Listo para Firma',
                        'firmado_en_folios' => 'Firmado en Folios',
                        'pagando_impuestos' => 'Pagando Impuestos',
                        'engargolado_para_rpp' => 'Engargolado para RPP',
                        'en_rpp' => 'En RPP',
                        'inscrito' => 'Inscrito en RPP',
                        'entregado' => 'Entregado al Cliente',
                    ])
                    ->placeholder('Todos'),

                SelectFilter::make('abogado_id')
                    ->label('Abogado')
                    ->relationship('abogado', 'name')
                    ->placeholder('Todos los abogados'),

                SelectFilter::make('tipo_operacion_id')
                    ->label('Tipo de Operación')
                    ->relationship('tiposOperacion', 'nombre')
                    ->placeholder('Todas las operaciones'),

                SelectFilter::make('institucion_credito_id')
                    ->label('Institución de Crédito')
                    ->relationship('institucionesCredito', 'nombre')
                    ->placeholder('Todas las instituciones'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

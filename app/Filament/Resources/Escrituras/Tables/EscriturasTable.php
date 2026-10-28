<?php

namespace App\Filament\Resources\Escrituras\Tables;

use App\Models\Avaluo;
use App\Models\Escritura;
use App\Models\PagosGestoria;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EscriturasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([25, 50, 100])
            ->searchDebounce('750ms')
            ->deferLoading()
            ->striped()
            ->columns([
                TextColumn::make('numero_escritura')
                    ->label('Escritura No.')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('expediente.numero_expediente')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('volumen')
                    ->label('Volumen')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => "Vol. {$state}"),

                TextColumn::make('fecha_firma')
                    ->label('Fecha Firma')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('firmas_completas')
                    ->label('Firmado')
                    ->boolean(),

                IconColumn::make('engargolada_lista')
                    ->label('Engargolado')
                    ->boolean(),

                TextColumn::make('folio_real_rpp')
                    ->label('Folio Real RPP')
                    ->placeholder('Pendiente')
                    ->copyable(),

                TextColumn::make('fecha_inscripcion_rpp')
                    ->label('Inscripción RPP')
                    ->date('d/m/Y')
                    ->placeholder('-'),

                TextColumn::make('fecha_entrega_cliente')
                    ->label('Entrega')
                    ->date('d/m/Y')
                    ->placeholder('-'),
            ])
            ->filters([
                TernaryFilter::make('firmas_completas')
                    ->label('Firmas Completas')
                    ->placeholder('Todos'),

                TernaryFilter::make('engargolada_lista')
                    ->label('Engargolado RPP')
                    ->placeholder('Todos'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('engargolar')
                    ->label('Engargolar')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->visible(fn (Escritura $record): bool => ! $record->engargolada_lista)
                    ->requiresConfirmation()
                    ->modalHeading('Validar Candado Notarial para Engargolado')
                    ->modalDescription('Se verificará que todos los pagos de gestoría cuenten con factura en secretaría y que los avalúos estén concluidos.')
                    ->action(function (Escritura $record) {
                        $expedienteId = $record->expediente_id;
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
                            $errores[] = "Avalúo pendiente de conclusión/entrega ({$avaluosPendientes->count()}).";
                        }

                        if (! empty($errores)) {
                            Notification::make()
                                ->title('Candado Notarial: Engargolado Bloqueado')
                                ->body('No es posible autorizar el engargolado. ' . implode(' | ', $errores))
                                ->danger()
                                ->persistent()
                                ->send();

                            return;
                        }

                        $record->update(['engargolada_lista' => true]);

                        Notification::make()
                            ->title('Escritura Lista para RPP')
                            ->body("La escritura No. {$record->numero_escritura} fue validada y marcada como engargolada lista.")
                            ->success()
                            ->send();
                    }),
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

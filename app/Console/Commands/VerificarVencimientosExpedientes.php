<?php

namespace App\Console\Commands;

use App\Models\Expediente;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class VerificarVencimientosExpedientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expedientes:verificar-vencimientos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica expedientes con vencimiento SLA bancario próximo o rebasado y notifica al personal asignado';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando verificación de vencimientos de expedientes SLA...');

        // Filtrar expedientes activos con fecha_limite_sla y que NO hayan ingresado ni concluido en RPP
        $expedientes = Expediente::query()
            ->whereNotNull('fecha_limite_sla')
            ->whereNotIn('estatus_proceso', ['en_rpp', 'inscrito', 'entregado'])
            ->with(['institucionesCredito', 'abogado', 'secretaria'])
            ->get();

        $now = now()->startOfDay();
        $notificados = 0;

        foreach ($expedientes as $expediente) {
            $limite = Carbon::parse($expediente->fecha_limite_sla)->startOfDay();
            $diasAlerta = (int) ($expediente->institucionesCredito?->dias_alerta_previa ?? 15);
            $diasRestantes = $now->diffInDays($limite, false);

            // Alerta si ya venció (diasRestantes < 0) o si faltan diasAlerta o menos
            if ($diasRestantes <= $diasAlerta) {
                $banco = $expediente->institucionesCredito?->nombre ?? 'Entidad Acreedora';

                if ($diasRestantes < 0) {
                    $diasVencido = abs($diasRestantes);
                    $titulo = "URGENTE: Expediente {$expediente->numero_expediente} VENCIDO";
                    $body = "El plazo SLA con {$banco} venció hace {$diasVencido} día(s) ({$limite->format('d/m/Y')}) y aún no ingresa a RPP.";
                    $color = 'danger';
                } else {
                    $titulo = "Alerta SLA: Expediente {$expediente->numero_expediente}";
                    $body = "Faltan {$diasRestantes} día(s) para el límite SLA con {$banco} ({$limite->format('d/m/Y')}). Presentar a RPP a la brevedad.";
                    $color = 'warning';
                }

                // Destinatarios: abogado y secretaria asignados
                $recipients = collect([$expediente->abogado, $expediente->secretaria])
                    ->filter()
                    ->unique('id');

                if ($recipients->isNotEmpty()) {
                    $notification = Notification::make()
                        ->title($titulo)
                        ->body($body)
                        ->actions([
                            Action::make('ver')
                                ->label('Ver Expediente')
                                ->url(url("/admin/expedientes/{$expediente->id}")),
                        ]);

                    if ($color === 'danger') {
                        $notification->danger();
                    } else {
                        $notification->warning();
                    }

                    $notification->sendToDatabase($recipients);
                    $notificados++;

                    $this->line("- [{$expediente->numero_expediente}] Notificación enviada a {$recipients->pluck('name')->implode(', ')} ({$diasRestantes} días)");
                }
            }
        }

        $this->info("Verificación finalizada. Se generaron alertas para {$notificados} expediente(s).");

        return Command::SUCCESS;
    }
}

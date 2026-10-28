<?php

namespace App\Filament\Widgets;

use App\Models\Expediente;
use App\Models\LotesFolio;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SlaAlertsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $now = now()->startOfDay();
        $todayStr = $now->toDateString();
        $in15DaysStr = $now->copy()->addDays(15)->toDateString();

        // 1. Expedientes en Tiempo:
        // Activos cuyo margen hacia la fecha límite sea > 15 días, o sin crédito bancario (particulares activos)
        $enTiempo = Expediente::query()
            ->whereNotIn('estatus_proceso', ['en_rpp', 'inscrito', 'entregado'])
            ->where(function ($q) use ($in15DaysStr) {
                $q->whereNull('fecha_limite_sla')
                    ->orWhere('fecha_limite_sla', '>', $in15DaysStr);
            })
            ->count();

        // 2. Expedientes por Vencer (Alerta Amarilla):
        // Activos con crédito donde fecha_limite_sla vence en 15 días o menos y aún no rebasan el día de hoy
        $porVencer = Expediente::query()
            ->whereNotNull('fecha_limite_sla')
            ->whereNotIn('estatus_proceso', ['en_rpp', 'inscrito', 'entregado'])
            ->where('fecha_limite_sla', '>=', $todayStr)
            ->where('fecha_limite_sla', '<=', $in15DaysStr)
            ->count();

        // 3. Expedientes Vencidos / Riesgo de Sanción:
        // Activos que ya rebasaron su fecha_limite_sla sin haber sido presentados al RPP
        $vencidos = Expediente::query()
            ->whereNotNull('fecha_limite_sla')
            ->whereNotIn('estatus_proceso', ['en_rpp', 'inscrito', 'entregado'])
            ->where('fecha_limite_sla', '<', $todayStr)
            ->count();

        // 4. Folios Oficiales Restantes:
        // Folios físicos disponibles en los lotes activos de Gobernación
        $foliosDisponibles = (int) LotesFolio::sum('folios_disponibles');

        return [
            Stat::make('Expedientes en Tiempo', $enTiempo)
                ->description('Margen operativo normal (> 15 días)')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::After)
                ->color('success'),

            Stat::make('Por Vencer (Alerta)', $porVencer)
                ->description('Límite vence en 15 días o menos')
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::After)
                ->color('warning'),

            Stat::make('Vencidos / En Riesgo', $vencidos)
                ->description('Plazo SLA rebasado sin ingresar a RPP')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::After)
                ->color('danger'),

            Stat::make('Folios Disponibles', number_format($foliosDisponibles))
                ->description('Hojas de protocolo en libro notarial')
                ->descriptionIcon('heroicon-o-document-text', IconPosition::After)
                ->color('info'),
        ];
    }
}

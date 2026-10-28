<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ExpedientesUrgentesTableWidget;
use App\Filament\Widgets\OperacionesMesChartWidget;
use App\Filament\Widgets\OperacionesPorInstitucionChartWidget;
use App\Filament\Widgets\SlaAlertsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Inicio';

    public function getWidgets(): array
    {
        return [
            SlaAlertsOverviewWidget::class,
            OperacionesMesChartWidget::class,
            OperacionesPorInstitucionChartWidget::class,
            ExpedientesUrgentesTableWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}

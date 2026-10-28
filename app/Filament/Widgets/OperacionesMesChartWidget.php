<?php

namespace App\Filament\Widgets;

use App\Models\Expediente;
use App\Models\TiposOperacion;
use Filament\Widgets\ChartWidget;

class OperacionesMesChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Volumen de Operaciones por Tipo de Acto';

    protected ?string $description = 'Distribución de expedientes iniciados en el periodo seleccionado';

    protected ?string $maxHeight = '300px';

    public ?string $filter = 'este_ano';

    protected function getFilters(): ?array
    {
        return [
            'este_mes' => 'Este Mes',
            'ultimo_trimestre' => 'Último Trimestre',
            'este_ano' => 'Este Año',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $now = now();

        match ($this->filter) {
            'este_mes' => [
                $inicio = $now->copy()->startOfMonth(),
                $fin = $now->copy()->endOfMonth(),
            ],
            'ultimo_trimestre' => [
                $inicio = $now->copy()->subMonths(3)->startOfDay(),
                $fin = $now->copy()->endOfDay(),
            ],
            default => [
                $inicio = $now->copy()->startOfYear(),
                $fin = $now->copy()->endOfYear(),
            ],
        };

        // Tipos de operación activos
        $tipos = TiposOperacion::query()
            ->where('activo', true)
            ->pluck('nombre', 'id');

        // Conteo agregado directamente en SQL para máximo rendimiento
        $conteos = Expediente::query()
            ->whereBetween('fecha_apertura', [$inicio->toDateString(), $fin->toDateString()])
            ->selectRaw('tipo_operacion_id, count(*) as total')
            ->groupBy('tipo_operacion_id')
            ->pluck('total', 'tipo_operacion_id');

        $labels = [];
        $data = [];
        $backgroundColors = [
            '#3b82f6', // azul
            '#f59e0b', // ámbar
            '#10b981', // esmeralda
            '#8b5cf6', // morado
            '#06b6d4', // cian
            '#ec4899', // rosa
        ];

        $colorIndex = 0;
        $barColors = [];

        foreach ($tipos as $id => $nombre) {
            $labels[] = $nombre;
            $data[] = (int) ($conteos[$id] ?? 0);
            $barColors[] = $backgroundColors[$colorIndex % count($backgroundColors)];
            $colorIndex++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Expedientes Aperturados',
                    'data' => $data,
                    'backgroundColor' => $barColors,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }
}

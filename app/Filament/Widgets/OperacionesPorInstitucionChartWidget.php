<?php

namespace App\Filament\Widgets;

use App\Models\Expediente;
use App\Models\InstitucionesCredito;
use Filament\Widgets\ChartWidget;

class OperacionesPorInstitucionChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Distribución por Entidad Financiera';

    protected ?string $description = 'Participación de créditos bancarios vs operaciones particulares';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $instituciones = InstitucionesCredito::query()
            ->where('activo', true)
            ->pluck('nombre', 'id');

        $conteos = Expediente::query()
            ->selectRaw('institucion_credito_id, count(*) as total')
            ->groupBy('institucion_credito_id')
            ->pluck('total', 'institucion_credito_id');

        $labels = [];
        $data = [];
        $palette = [
            '#64748b', // gris pizarra (particular)
            '#dc2626', // rojo santander
            '#d97706', // naranja infonavit
            '#2563eb', // azul bancario
            '#059669', // verde
            '#7c3aed', // violeta
        ];

        $colors = [];
        $index = 0;

        foreach ($instituciones as $id => $nombre) {
            $total = (int) ($conteos[$id] ?? 0);
            if ($total > 0 || count($conteos) === 0) {
                $labels[] = $nombre;
                $data[] = $total;
                $colors[] = $palette[$index % count($palette)];
                $index++;
            }
        }

        if (empty($labels)) {
            foreach ($instituciones as $id => $nombre) {
                $labels[] = $nombre;
                $data[] = 0;
                $colors[] = $palette[$index % count($palette)];
                $index++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Expedientes por Institución',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }
}

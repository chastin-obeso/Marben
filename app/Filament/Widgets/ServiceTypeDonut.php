<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use App\Models\ServiceInvoice;
use App\Models\ServiceType;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ServiceTypeDonut extends ApexChartWidget
{
    protected static ?string $chartId = 'serviceTypeDonut';
    protected static ?string $heading = 'Total Sales by Service Type';

    protected function getOptions(): array
    {
        $serviceTypes = ServiceType::pluck('service')->toArray();

        $data = [];
        foreach ($serviceTypes as $service) {
            $total = ServiceInvoice::query()
                ->join('bills', 'service_invoices.bill_id', '=', 'bills.id')
                ->join('job_orders', 'bills.job_order_id', '=', 'job_orders.id')
                ->join('service_types', 'job_orders.service_type_id', '=', 'service_types.id')
                ->where('service_types.service', $service)
                ->sum('service_invoices.amount_paid');

            $data[] = $total;
        }

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 400,
            ],
            'series' => $data,
            'labels' => $serviceTypes,
            'colors' => ['#A3E635', '#22D3EE', '#FBBF24', '#F87171', '#818CF8'], // add more if needed
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            tooltip: {
                y: {
                    formatter: function(val) { return '₱' + val.toLocaleString(); }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.globals.labels[opts.seriesIndex] + ': ' + val.toLocaleString() + '%';
                }
            }
        }
        JS);
    }
}

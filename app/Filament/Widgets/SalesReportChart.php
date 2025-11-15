<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use App\Models\ServiceInvoice;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Carbon;

class SalesReportChart extends ApexChartWidget
{
    protected static ?string $chartId = 'salesReportChart';
    protected static ?string $heading = 'Sales by Service Type (Monthly)';

    protected function getOptions(): array
    {
        // Initialize months
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->format('M'))->toArray();

        // Get service types
        $serviceTypes = \App\Models\ServiceType::pluck('service')->toArray();

        // Prepare series data
        $series = [];
        foreach ($serviceTypes as $service) {
            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $total = ServiceInvoice::query()
                    ->join('bills', 'service_invoices.bill_id', '=', 'bills.id')
                    ->join('job_orders', 'bills.job_order_id', '=', 'job_orders.id')
                    ->join('service_types', 'job_orders.service_type_id', '=', 'service_types.id')
                    ->where('service_types.service', $service)
                    ->whereMonth('service_invoices.payment_date', $month)
                    ->sum('service_invoices.amount_paid');

                $monthlyData[] = $total;
            }

            $series[] = [
                'name' => $service,
                'data' => $monthlyData,
            ];
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'stacked' => true, // optional: stacked bars
            ],
            'series' => $series,
            'xaxis' => [
                'categories' => $months,
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'colors' => ['#A3E635', '#22D3EE', '#FBBF24', '#F87171', '#818CF8'], // add more if needed
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function (val) { return '₱' + val.toLocaleString(); }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) { return '₱' + val.toLocaleString(); },
                dropShadow: { enabled: true },
            },
            tooltip: {
                y: {
                    formatter: function(val) { return '₱' + val.toLocaleString(); }
                }
            }
        }
        JS);
    }
}

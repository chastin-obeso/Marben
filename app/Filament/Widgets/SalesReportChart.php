<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use App\Models\ServiceInvoice;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\ServiceType;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class SalesReportChart extends ApexChartWidget
{
    protected static ?string $chartId = 'salesReportChart';
    protected static ?string $heading = null; // optional default

    

    use HasFiltersSchema;

    protected function getOptions(): array
    {
        $year = $this->filters['target_year'] ?? now()->year;

        // Initialize months
        $months = collect(range(1, 12))
            ->map(fn($m) => Carbon::create()->month($m)->format('M'))
            ->toArray();

        // Get service types
        $serviceTypes = ServiceType::pluck('service')->toArray();

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
                    ->whereYear('service_invoices.payment_date', $year)  // filter by year
                    ->whereMonth('service_invoices.payment_date', $month)
                    ->sum('service_invoices.amount_paid');

                $monthlyData[] = $total;
            }

            $series[] = [
                'name' => $service,
                'data' => $monthlyData,
            ];
        }

        // Check if all series are empty
        $isEmpty = collect($series)->flatMap(fn($s) => $s['data'])->every(fn($v) => $v == 0);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'stacked' => true,
            ],
            'series' => $isEmpty ? [] : $series,
            'xaxis' => [
                'categories' => $months,
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'colors' => [
                // Greens
                // '#D9F871', // very light lime
                '#A3E635', // bright lime
                // '#7BC22B', // medium green
                '#55871F', // dark green
                // '#36590F', // very dark green
                
                // Yellows
                // '#FEF3C7', // very light yellow
                '#FACC15', // bright yellow
                // '#EAB308', // medium yellow
                '#CA8A04', // dark yellow
                // '#A16207', // very dark yellow

                // Oranges
                // '#FFEDD5', // very light orange
                '#FB923C', // bright orange
                // '#F97316', // medium orange
                '#EA580C', // dark orange
                // '#C2410C', // very dark orange

                // Reds
                // '#FEE2E2', // very light red
                '#F87171', // bright red
                // '#EF4444', // medium red
                '#DC2626', // dark red
                // '#991B1B', // very dark red
            ],

            'noData' => [
                'text' => "No data available for {$year}",
                'align' => 'center',
                'verticalAlign' => 'middle',
                'style' => ['fontSize' => '16px'],
            ],
        ];
    }
    protected function getHeading(): ?string
    {
        return 'Revenue by Service Type (' . ($this->filters['target_year'] ?? now()->year) . ')';
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

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('target_year')
                ->label('Select Year')
                ->options(
                    collect(range(now()->year, now()->year - 10))
                        ->mapWithKeys(fn ($year) => [$year => $year])
                )
                ->required(),
        ]);
    }

    public function updatedInteractsWithSchemas(string $statePath): void
    {
        $this->updateOptions();
    }
}

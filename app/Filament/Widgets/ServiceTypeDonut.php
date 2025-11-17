<?php

namespace App\Filament\Widgets;

use App\Models\ServiceType;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use App\Models\ServiceInvoice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class ServiceTypeDonut extends ApexChartWidget
{
    protected static ?string $chartId = 'serviceTypeDonut';
    protected static ?string $heading = null;

    use HasFiltersSchema;

    public $target_year; // holds the selected year

    protected function getOptions(): array
    {
        $year = $this->filters['target_year'] ?? today()->year;
        $serviceTypes = ServiceType::pluck('service')->toArray();
        $data = [];
        
        foreach ($serviceTypes as $service) {
            $total = ServiceInvoice::query()
                ->join('bills', 'service_invoices.bill_id', '=', 'bills.id')
                ->join('job_orders', 'bills.job_order_id', '=', 'job_orders.id')
                ->join('service_types', 'job_orders.service_type_id', '=', 'service_types.id')
                ->where('service_types.service', $service)
                ->whereYear('service_invoices.payment_date', $year)   // ← FILTER APPLIED HERE
                ->sum('service_invoices.amount_paid');

            $data[] = $total;
        }

        $isEmpty = collect($data)->every(fn ($value) => $value == 0);

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 400,
            ],
            'series' => $isEmpty ? [] : $data,
            'labels' => $isEmpty ? [] : $serviceTypes,
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
                'style' => [
                    'fontSize' => '16px',
                ],
            ],
        ];
    }
    
    protected function getHeading(): ?string
    {
       $formatNumber = fn(int $number): string => match (true) {
            $number < 1000 => (string) $number,
            $number < 1000000 => round($number / 1000, 2) . 'k',
            default => round($number / 1000000, 2) . 'm',
        };
        return 'Revenue by Service Type (' . ($this->filters['target_year'] ?? now()->year) . ') - Total: ' . $formatNumber(array_sum($this->getOptions()['series']));
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

<?php

namespace App\Filament\Resources\JobOrders\Pages;

use App\Filament\Resources\JobOrders\JobOrderResource;
use App\Models\JobOrder;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListJobOrders extends ListRecords
{
    protected static string $resource = JobOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('New Job Order')
                ->mutateDataUsing(function($data) {
                    // dd(array_merge($data, $this->generateLastJobOrderNumber()));
                    $data['status'] = 'Pending';
                    // dd($data);
                    return array_merge($data, $this->generateLastJobOrderNumber());
                })
                ->closeModalByClickingAway(false),
        ];
    }

    public function generateLastJobOrderNumber(): array
    {
        $job_order = JobOrder::whereYear('date_requested', now()->year)->latest('series')->first();
        $series = $job_order?->series + 1;
        return [
            'series' => $series,
            'job_order_number' => '#JO' . sprintf('%05d', $series)
        ];

    }
}

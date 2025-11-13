<?php

namespace App\Filament\Resources\JobOrders\Pages;

use App\Models\Bill;
use App\Models\JobOrder;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\JobOrders\JobOrderResource;

class ListJobOrders extends ListRecords
{
    protected static string $resource = JobOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('New Job Order')
                ->mutateDataUsing(function($data) {
                    $data['status'] = 'Scheduled';
                    $data['date_requested'] = now();
                    $data['service_fee_status'] = 'Unbilled';
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
            'job_order_number' => 'JO#' . sprintf('%05d', $series)
        ];

    }

    
}

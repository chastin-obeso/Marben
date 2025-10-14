<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Models\Bill;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Bills\BillResource;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [      
            CreateAction::make('create_bill')
                ->label('Create Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Add a New Bill Record')
                ->mutateDataUsing(function($data) {
                    // dd(array_merge($data, $this->generateLastJobOrderNumber()));
                    $data['status'] = 'Pending';
                    $data['amount_due'] = $data['total_amount'];
                    $data['bill_date'] = now();
                    return array_merge($data, $this->generateLastBillNumber());
                })
                ->closeModalByClickingAway(false),
        ];
    }

    public function generateLastBillNumber(): array
    {
        $bill = Bill::whereYear('bill_date', now()->year)->latest('bill_series')->first();
        $series = $bill?->bill_series + 1;
        return [
            'bill_series' => $series,
            'bill_number' => 'BILL#' . sprintf('%05d', $series)
        ];

    }

}

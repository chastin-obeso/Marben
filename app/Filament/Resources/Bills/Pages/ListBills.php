<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Models\Bill;
use App\Models\JobOrder;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
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
                ->mutateDataUsing(function(array $data) {
                    $data['status'] = 'Unpaid';
                    $data['amount_due'] = $data['total_amount'];
                    $data['bill_date'] = now();
                    return array_merge($data, $this->generateLastBillNumber());
                })
                ->successNotification(
                    Notification::make()
                        ->title('Bill Created Successfully')
                        ->success()
                )
                ->after(function (array $data, $record) {
                        $jobOrderId = $data['job_order_id'];
        
                        // 1. Update the parent Job Order status
                        $jobOrder = JobOrder::find($jobOrderId);
                        if ($jobOrder) {
                            if ($jobOrder->service_fee_bill_id == null) {
                                $jobOrder->service_fee_bill_id = $record->id;
                            }
                            $jobOrder->save();
                        }
                        // dd($data);
                        // 2. Mark the individual unbilled Job Order Parts with the new Bill's ID
                        JobOrder::find($jobOrderId)
                            ->unbilledJobOrderParts()
                            ->update(['bill_id' =>  $record->id]);
                        JobOrder::find($data['job_order_id'])->logs()->create([
                            'details' => 'Created Bill #' . $data['bill_number'],
                            'date' => now(),
                        ]);        
                })
                       
                ->closeModalByClickingAway(false),
        ];
    }

    public function generateLastBillNumber(): array
    {
        $bill = Bill::withTrashed()->whereYear('bill_date', now()->year)->latest('bill_series')->first();
        $series = $bill?->bill_series + 1;
        return [
            'bill_series' => $series,
            'bill_number' => 'BILL#' . sprintf('%05d', $series)
        ];

    }

}

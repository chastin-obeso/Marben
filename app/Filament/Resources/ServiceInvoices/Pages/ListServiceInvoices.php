<?php

namespace App\Filament\Resources\ServiceInvoices\Pages;

use App\Models\Bill;
use App\Models\ServiceInvoice;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;

class ListServiceInvoices extends ListRecords
{
    protected static string $resource = ServiceInvoiceResource::class;
    public bool $canDelete = false;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('create_service_invoice')
                ->label('Pay Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Pay Bill')
                ->mutateDataUsing(function($data) {
                    $data['payment_date'] = now();
                    return array_merge($data, $this->generateLastServiceInvoiceNumber());
                })
                 ->action(function (array $data) {
                        $invoice = ServiceInvoice::create($data);
                        $bill = $invoice->bill; 
                        if ($bill) {
                            $bill->amount_due -= $invoice->amount_paid;
                            $bill->save();
                        }
                        $invoice->bill->updatePaymentStatus($bill->jobOrder);
                    })
                ->successNotification(
                    Notification::make()
                        ->title('Service Invoice Created Successfully')
                        ->success()
                )
                ->closeModalByClickingAway(false),
        ];
    }

    public function generateLastServiceInvoiceNumber(): array
    {
        $invoice = ServiceInvoice::withTrashed()->whereYear('payment_date', now()->year)->latest('service_invoice_series')->first();
        $series = $invoice?->service_invoice_series + 1;
        return [
            'service_invoice_series' => $series,
            'service_invoice_number' => 'SI#' . sprintf('%05d', $series)
        ];

    }

    
}

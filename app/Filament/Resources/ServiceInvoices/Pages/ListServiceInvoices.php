<?php

namespace App\Filament\Resources\ServiceInvoices\Pages;

use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceInvoices extends ListRecords
{
    protected static string $resource = ServiceInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('create_service_invoice')
                ->label('Pay Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Pay Bill')
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
}

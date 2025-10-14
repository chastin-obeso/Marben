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
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ServiceInvoices\Pages;

use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceInvoice extends CreateRecord
{
    protected static string $resource = ServiceInvoiceResource::class;
}

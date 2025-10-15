<?php

namespace App\Filament\Resources\ServiceInvoices\Pages;

use Models\ServiceInvoice;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;

class CreateServiceInvoice extends CreateRecord
{
    protected static string $resource = ServiceInvoiceResource::class;
    
}

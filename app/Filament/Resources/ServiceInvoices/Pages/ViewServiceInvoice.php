<?php

namespace App\Filament\Resources\ServiceInvoices\Pages;

use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceInvoice extends ViewRecord
{
    protected static string $resource = ServiceInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

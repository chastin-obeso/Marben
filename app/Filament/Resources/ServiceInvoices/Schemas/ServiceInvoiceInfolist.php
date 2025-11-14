<?php

namespace App\Filament\Resources\ServiceInvoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('service_invoice_number')->label('Invoice Number'),
                TextEntry::make('amount_paid')->label('Amount Paid')->money('PHP', true),
                TextEntry::make('payment_type')->label('Payment Type'),
                TextEntry::make('reference_number')->label('Reference Number')->state(fn ($record) => $record->reference_number ?? 'N/A'),
                TextEntry::make('payment_date')->label('Payment Date')->date(),
                TextEntry::make('bill.bill_number')->label('Bill Number'),
            ]);
    }
}

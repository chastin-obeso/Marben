<?php

namespace App\Filament\Resources\Bills\Schemas;

use Dom\Text;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bill Information')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('bill_date')->date(),
                        TextEntry::make('due_date')->date(),
                        TextEntry::make('total_amount')->money('PHP', true),
                        TextEntry::make('amount_due')->money('PHP', true),
                        TextEntry::make('status'),
                        TextEntry::make('JobOrder.job_order_number')->label('Job Order #'),
                        TextEntry::make('particulars')->html(),
                    ])
            ]);
    }
}

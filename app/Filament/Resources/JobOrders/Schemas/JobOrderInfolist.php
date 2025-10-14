<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JobOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('job_order_number'),
                TextEntry::make('ServiceType.service')
                    ->label('Service Type'),
                TextEntry::make('description'),
                TextEntry::make('date_requested')
                    ->date(),
                TextEntry::make('date_started')
                    ->date(),
                TextEntry::make('date_targed')
                    ->date(),
                TextEntry::make('date_finished')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('customer.name')
                    ->label('Customer'),
            ]);
    }
}

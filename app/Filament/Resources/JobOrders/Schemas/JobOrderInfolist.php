<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Basic information about the job order.')
                    ->columnSpanFull()
                    ->columns(5)
                    ->components([
                        TextEntry::make('job_order_number')
                            ->label('Job Order Number'),
                        TextEntry::make('ServiceType.service')
                            ->label('Service Type'),
                        TextEntry::make('customer.name')
                            ->label('Customer'),
                        TextEntry::make('status'),
                        TextEntry::make('reJobOrder.job_order_number')
                            ->label('Re-Job Order Number'),
                            
                ]),
                Section::make('Description')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->html(),
                ]),
                Section::make('Other Information')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('date_requested')
                            ->date(),
                        TextEntry::make('date_started')
                            ->date(),
                        TextEntry::make('date_target')
                            ->label('Target Date')
                            ->date(),
                        TextEntry::make('date_finished')
                            ->date(),
  
                ]),
                
            ]);
    }
}

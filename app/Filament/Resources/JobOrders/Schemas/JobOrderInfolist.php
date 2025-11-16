<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Dom\Text;
use App\Models\Bill;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;

class JobOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Basic information about the job order.')
                    ->columnSpanFull()
                    ->columns(4)
                    ->components([
                        TextEntry::make('ServiceType.service')
                            ->label('Service Type'),
                        TextEntry::make('customer.name')
                            ->label('Customer'),
                        TextEntry::make('status'),
                        TextEntry::make('user_id')
                            ->label('Assigned Employee')
                            ->formatStateUsing(fn ($state, $record) => $record->user ? $record->user->name : 'Unassigned'),
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
                Section::make('Description')
                    ->collapsible()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('description')
                            ->formatStateUsing(fn ($state) => $state== '<p></p>' ? 'No description provided.' : $state)
                            ->hiddenLabel()
                            ->html(),
                ]),
                Section::make('Service Fee Information')
                    ->description('Details about the service fee.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        TextEntry::make('service_fee')
                            ->label('Service Fee')
                            ->prefix('₱')
                            ->formatStateUsing(fn ($state) => number_format($state, 2)),
                        TextEntry::make('service_fee_bill_id')
                            ->color(fn($record) => $record->service_fee_bill_id == null ? 'danger' : 'success')
                            ->state( fn ($record) => $record->service_fee_bill_id == null ? 'Unbilled' : 'Billed '.'('.Bill::find($record->service_fee_bill_id)->bill_number.')' )
                            ->label('Service Fee Status'),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JobOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('job_order_number')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('service_type')
                    ->options([
                        'Layout' => 'Layout',
                        'Programs' => 'Programs',
                        'Printing/Binding' => 'Printing/Binding',
                        'Repair' => 'Repair',
                    ])
                    ->required(),
                TextInput::make('description'),
                DatePicker::make('date_requested'),
                DatePicker::make('date_started'),
                DatePicker::make('date_targed'),
                DatePicker::make('date_finished'),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}

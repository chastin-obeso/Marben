<?php

namespace App\Filament\Resources\Bills\Schemas;

use BcMath\Number;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₱'),
                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(now())
                    ->minDate(now()),
                TextInput::make('particulars')
                    ->label('Particulars')
                    ->required()
                    ->maxLength(255),
                Select::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('JobOrder', 'job_order_number')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Bills\Schemas;

use BcMath\Number;
use Livewire\Component;
use App\Models\JobOrder;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Livewire;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\ServiceInvoices\Pages\CreateServiceInvoice;
use Filament\Forms\Components\ViewField;

class BillForm
{
    
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->disabled()
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₱'),
                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(now())
                    ->minDate(now()),
                Select::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('JobOrder', 'job_order_number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            // 1. Fetch the unbilled parts using the model method
                            $parts = JobOrder::find($state)?->unbilledJobOrderParts;
                            
                            // 2. Convert to an array and store this array in the form's data state
                            $set('parts_data', $parts->toArray());
                            $set('total_amount', $parts->sum('total_price'));
                        } else {
                            $set('parts_data', []);
                        }
                    }),
                Section::make('Particulars of Unbilled Job Order Parts')
                    ->columnSpanFull()
                    ->components([
                        ViewField::make('parts_data')
                        ->live()
                        ->view('filament.infolists.entries.job-order-parts-table'),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ServiceInvoices\Schemas;

use Dom\Text;
use App\Models\Bill;
use Filament\Schemas\Schema;
use App\Models\ServiceInvoice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;

class ServiceInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bill.amount_due')
                    ->label('Amount Due')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->prefix('₱'),
                TextInput::make('amount_paid')
                    ->label('Amount Paid')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->minValue(1)
                    ->maxValue(fn (callable $get) => 
                        $get ('bill.amount_due')
                    )
                    ->prefix('₱'),
                Select::make('payment_type')
                    ->label('Payment Type')
                    ->options([
                        'Cash' => 'Cash',
                        'GCash' => 'GCash',
                    ])
                    ->preload()
                    ->reactive()
                    ->required(),
                Select::make('bill_id')
                    ->label('Bill')
                    ->relationship('Bill', 'bill_number')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->options(function (Get $get) {
                        $fullyPaidBillIds = ServiceInvoice::pluck('bill_id')->toArray();
                        return Bill::whereNotIn('id', $fullyPaidBillIds)
                            ->where(function ($query) {
                                $query->whereNot('status', 'Fully Paid');
                            })
                            ->pluck('bill_number', 'id');
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        $bill = Bill::find($state);
                        $set('bill.amount_due', $bill?->amount_due ?? 0);
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $bill = Bill::find($state);
                        $set('bill.amount_due', $bill?->amount_due ?? 0);
                    })
                    ->required(),
                TextInput::make('reference_number')
                    ->label('Reference Number')
                    ->maxLength(255)
                    ->required()
                    ->hidden(fn (callable $get) => $get('payment_type') != 'GCash'),
            ]);
    }
}

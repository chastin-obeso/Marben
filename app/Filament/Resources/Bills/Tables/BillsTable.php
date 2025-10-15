<?php

namespace App\Filament\Resources\Bills\Tables;

use Dom\Text;
use App\Models\Bill;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ServiceInvoice;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')
                    ->label('Bill #')
                    ->toggleable()
                    ->color(
                        fn ($record) => $record->deleted_at ? 'danger' : 'success'
                    )
                    ->searchable(),
                TextColumn::make('JobOrder.job_order_number')
                    ->label('Job Order #')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('JobOrder.customer.name')
                    ->label('Customer')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Total Amount')
                    ->money('PHP', true)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('amount_due')
                    ->label('Amount Due')
                    ->money('PHP', true)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('bill_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('createBill')
                ->button()
                ->label('Pay Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading(fn($record) => 'Pay ' . $record->bill_number)
                ->schema([
                    TextInput::make('amount_due')
                        ->label('Amount Due')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false) // display-only, not saved
                        ->default(fn ($record) => $record->amount_due)
                        ->prefix('₱'),
                    TextInput::make('amount_paid')
                        ->label('Amount Paid')
                        ->required()
                        ->numeric()
                        ->reactive()
                        ->maxValue(fn (callable $get) => 
                            $get ('amount_due')
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
                    TextInput::make('reference_number')
                        ->label('Reference Number')
                        ->maxLength(255)
                        ->required()
                        ->hidden(fn (callable $get) => $get('payment_type') != 'GCash'),
                ]
            )
            ->action(function (array $data, Bill $record) {
                $invoice = ServiceInvoice::create([
                        'service_invoice_number' => BillsTable::generateLastServiceInvoiceNumber()['service_invoice_number'],
                        'service_invoice_series' => BillsTable::generateLastServiceInvoiceNumber()['service_invoice_series'],
                        'amount_paid'            => $data['amount_paid'],
                        'payment_type'           => $data['payment_type'],
                        'payment_date'           => now(),
                        'bill_id'                => $record->id,
                        'reference_number'       => $data['reference_number'] ?? null,
                ]);
                $bill = $invoice->bill;
                        if ($bill) {
                            $bill->amount_due -= $invoice->amount_paid;
                            $bill->save();
                        }
                    })
                ->closeModalByClickingAway(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function generateLastServiceInvoiceNumber(): array
    {
        $invoice = ServiceInvoice::whereYear('payment_date', now()->year)->latest('service_invoice_series')->first();
        $series = $invoice?->service_invoice_series + 1;
        return [
            'service_invoice_series' => $series,
            'service_invoice_number' => 'SI#' . sprintf('%05d', $series)
        ];

    }

}

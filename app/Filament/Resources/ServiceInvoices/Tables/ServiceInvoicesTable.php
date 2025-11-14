<?php

namespace App\Filament\Resources\ServiceInvoices\Tables;

use components;
use App\Models\Bill;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ServiceInvoice;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\View;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Schemas\Components\Section;

class ServiceInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_invoice_number')
                    ->label('Invoice Number')
                    ->searchable()
                    ->toggleable()
                    ->color(
                        fn ($record) => $record->deleted_at ? 'danger' : 'success'
                    )
                    ->sortable(),
                TextColumn::make('amount_paid')
                    ->label('Amount Paid')
                    ->prefix('₱')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('reference_number')
                    ->label('Reference Number')
                    ->state(fn ($record) => $record->reference_number ?? 'N/A')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->date()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('bill.bill_number')
                    ->label('Bill Number')
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                    SelectFilter::make('payment_type')
                    ->label('Payment Type')
                    ->options([
                        'Cash' => 'Cash',
                        'GCash' => 'GCash',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                ->modalHeading('Service Invoice Details'),
                EditAction::make()
                    ->visible(fn (ServiceInvoice $record) => !$record->deleted_at)
                    ->schema([
                        Section::make()
                        ->columns(2)
                        ->schema([
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
                                ->validationMessages([
                                    'maxValue' => 'The updated amount paid exceeds the amount due on the bill.',
                                ])
                                ->minValue(0)
                                ->maxValue(fn (callable $get, ServiceInvoice $record) => 
                                    $get ('bill.amount_due') + $record->amount_paid
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
                                ->disabled()
                                ->hintIcon('heroicon-o-information-circle', tooltip: 'Bill cannot be changed once set.')
                                ->label('Bill')
                                ->relationship('Bill', 'bill_number')
                                ->searchable()
                                ->preload()
                                ->reactive()
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
                        ])
                    ])
                    ->action(function (array $data, ServiceInvoice $record) {
                        $invoice = $record;
                        $bill = $invoice->bill; 
                        if ($bill) {
                            $totalPaid = $bill->serviceInvoices()->sum('amount_paid');
                            $totalPaid -= $invoice->amount_paid;
                            $totalPaid += $data['amount_paid'];
                            $bill->amount_due = $bill->total_amount - $totalPaid;
                            $bill->save();
                            $bill->updatePaymentStatus($bill->jobOrder); 
                        }
                        $invoice->update($data);
                    }),
                // Action::make('refund')
                //     ->visible(fn (ServiceInvoice $record) => !$record->deleted_at)
                //     ->label('Refund')
                //     ->icon('heroicon-o-currency-dollar')
                //     ->color('danger')
                //     ->requiresConfirmation()
                //     ->modalHeading('Confirm Refund')
                //     ->modalDescription('Are you sure you want to refund this payment? This action cannot be undone.')
                //     ->action(function (ServiceInvoice $invoice) {
                //         $invoice->delete();
                //         $bill = $invoice->bill;
                //         if ($bill) {
                //             $bill->amount_due += $invoice->amount_paid;
                //             $bill->save();
                //         }
                //         $invoice->bill->updatePaymentStatus(false);
                //     }),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                //     ForceDeleteBulkAction::make(),
                //     RestoreBulkAction::make(),
                // ]),
            ]);
    }
}

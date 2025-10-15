<?php

namespace App\Filament\Resources\ServiceInvoices\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ServiceInvoice;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Refund')
                    ->modalDescription('Are you sure you want to refund this payment? This action cannot be undone.')
                    ->action(function (ServiceInvoice $invoice) {
                        $invoice->delete();
                        $bill = $invoice->bill;
                        if ($bill) {
                            $bill->amount_due += $invoice->amount_paid;
                            $bill->save();
                        }
                        $invoice->bill->updatePaymentStatus(false);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

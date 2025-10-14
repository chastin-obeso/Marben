<?php

namespace App\Filament\Resources\Bills\Tables;

use Dom\Text;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
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

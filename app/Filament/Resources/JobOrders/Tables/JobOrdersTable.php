<?php

namespace App\Filament\Resources\JobOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_number')
                    ->label('Job Order #')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable()
                    ->html(),
                TextColumn::make('date_requested')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_started')
                    ->date()
                    ->hidden() // to remove bloat in the table (too much info can kill a victorian era child)
                    ->searchable(),
                    //->sortable(),
                TextColumn::make('date_targed')
                    ->date()
                    ->hidden() // to remove bloat in the table (too much info can kill a victorian era child)
                    ->searchable(),
                    //->sortable(),
                TextColumn::make('date_finished')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

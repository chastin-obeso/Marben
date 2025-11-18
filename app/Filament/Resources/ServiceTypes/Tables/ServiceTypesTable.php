<?php

namespace App\Filament\Resources\ServiceTypes\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class ServiceTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service')
                    ->searchable(),
                TextColumn::make('job_orders_count')
                    ->label('No. of Job Orders')
                    ->numeric()
                    ->state(function ($record) {
                        return $record->job_orders->count();
                    }),
                TextColumn::make('ongoing_job_orders_count')
                    ->label('Ongoing Job Orders')
                    ->numeric()
                    ->toggleable()
                    ->state(function ($record) {
                        return $record->job_orders()->whereNotIn('status', ['Completed', 'Closed', 'Cancelled'])->count();
                    }),
                TextColumn::make('completed_job_orders_count')
                    ->label('Completed Job Orders')
                    ->numeric()
                    ->toggleable()
                    ->state(function ($record) {
                        return $record->job_orders()->where('status', 'Completed')->count();
                    }),
                TextColumn::make('closed_job_orders_count')
                    ->label('Closed Job Orders')
                    ->numeric()
                    ->toggleable()
                    ->state(function ($record) {
                        return $record->job_orders()->where('status', 'Closed')->count();
                    }),
                TextColumn::make('cancelled_job_orders_count')
                    ->label('Cancelled Job Orders')
                    ->numeric()
                    ->toggleable()
                    ->state(function ($record) {
                        return $record->job_orders()->where('status', 'Cancelled')->count();
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                ->disabled(fn ($record) => $record->job_orders()->exists())
                ->tooltip(fn ($record) => $record->job_orders()->exists() ? 'Cannot delete Service Type with associated Job Orders.' : null),
            ])
            ->toolbarActions([
              
            ]);
    }
}

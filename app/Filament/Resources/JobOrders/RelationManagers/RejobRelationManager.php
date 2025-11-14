<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\JobOrders\JobOrderResource;
use Filament\Resources\RelationManagers\RelationManager;

class RejobRelationManager extends RelationManager
{
    protected static string $relationship = 'reJobOrder';

    protected static ?string $relatedResource = JobOrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Rejobs')
            ->columns([
                TextColumn::make('job_order_number')
                    ->label('Job Order #')
                    ->toggleable()
                    ->searchable()
                    ->color(
                        fn ($record) => now()->toDateString() > $record->date_target && $record->status !== 'Completed' && $record->status !== 'Closed' ? 'danger' : 'success'
                    )
                    ->sortable(),
                TextColumn::make('serviceType.service')
                    ->label('Service')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->html(),
                TextColumn::make('date_requested')
                    ->date()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('date_started')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_target')
                    ->label('Target Date')
                    ->date()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_finished')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
            ]);
    }
}

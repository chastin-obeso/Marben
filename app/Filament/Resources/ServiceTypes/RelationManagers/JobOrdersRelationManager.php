<?php

namespace App\Filament\Resources\ServiceTypes\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\JobOrders\JobOrderResource;
use Filament\Resources\RelationManagers\RelationManager;

class JobOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'job_orders';

    protected static ?string $title = 'Job Orders';

    // protected static ?string $relatedResource = JobOrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Job Orders')
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('job_order_number')
                    ->label('Job Order #')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('serviceType.service')
                    ->label('Service')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->toggleable()
                    ->searchable(),
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
                    ->searchable(),
                    //->sortable(),
                TextColumn::make('date_target')
                    ->label('Target Date')
                    ->date()
                    ->toggleable()
                    ->searchable(),
                    //->sortable(),
                TextColumn::make('date_finished')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->toggleable()
                    ->searchable(),
            ]);
    }
}

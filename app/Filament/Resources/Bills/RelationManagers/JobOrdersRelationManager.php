<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class JobOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'job_orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Part Name')
                    ->required(),
                TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                        ->minValue(0)
                        ->rule('decimal:0,2')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),
                TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                        ->minValue(0)
                        ->rule('decimal:0,2')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Job Order Parts')
            ->columns([
                TextColumn::make('name')->label('Part Name')->searchable()->sortable(),
                TextColumn::make('unit_price')->label('Unit Price')->money('php', true)->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('total_price')->label('Total Price')->money('php', true)->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

class PartsRelationManager extends RelationManager
{
    protected static string $relationship = 'Parts';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Part Name')
                    ->required(),
                TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn (Get $get, Set $set) =>
                        $set('total_price', $get('unit_price') * $get('quantity'))
                    )
                    ->numeric()
                        ->minValue(0)
                        ->rule('decimal:0,2')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn (Get $get, Set $set) =>
                        $set('total_price', $get('unit_price') * $get('quantity'))
                    )
                    ->numeric()
                    ->required(),
                TextInput::make('total_price')
                    ->label('Total Price')
                    ->disabled()
                    ->reactive()
                    ->dehydrated()
                    ->numeric()
                        ->minValue(0)
                        ->rule('decimal:0,2')
                    ->required(),
                // Select::make('status')
                //     ->label('Status')
                //     ->options([
                //         'Pending' => 'Pending',
                //         'In Progress' => 'In Progress',
                //         'Completed' => 'Completed',
                //         'Cancelled' => 'Cancelled',
                //     ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Materials/Parts')
            ->recordTitleAttribute('Job Order Parts')
            ->defaultSort('job_order_id', 'desc')
            ->columns([
                TextColumn::make('name')->label('Part Name')->searchable()->sortable(),
                TextColumn::make('unit_price')->label('Unit Price')->money('php', true)->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('total_price')->label('Total Price')->money('php', true)->sortable(),
                TextColumn::make('job_order_id')
                ->label('Status')
                ->sortable()
                ->formatStateUsing(function ($record) {
                    if($record->bill_id !== null) {
                        return 'Billed';
                    } 
                    else 
                    {
                        return 'Unbilled';
                    }
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('add_part')
                    ->label('Add Material/Part')
                    ->icon('heroicon-o-plus')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (Get $get, Set $set) =>
                                $set('total_price', $get('unit_price') * $get('quantity'))
                            )
                            ->numeric()
                                ->minValue(0)
                                ->rule('decimal:0,2')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (Get $get, Set $set) =>
                                $set('total_price', $get('unit_price') * $get('quantity'))
                            )
                            ->numeric()
                            ->required(),
                        TextInput::make('total_price')
                            ->label('Total Price')
                            ->disabled()
                            ->reactive()
                            ->dehydrated()
                            ->numeric()
                                ->minValue(0)
                                ->rule('decimal:0,2')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $this->ownerRecord->parts()->create($data);
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

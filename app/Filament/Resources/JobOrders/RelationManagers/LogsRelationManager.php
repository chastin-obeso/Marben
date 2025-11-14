<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
            public function canCreate(): bool
            {
                return true;
            }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('details')
                    ->required(),
                TextInput::make('date')
                    ->default(now())
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Job Order Log')
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('details')->label('Details')->searchable(),
                TextColumn::make('date')->label('Date')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('add_log')
                    ->icon('heroicon-o-plus')
                    ->label('Add Log')
                    ->schema([
                        TextInput::make('details')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $data['date'] = now();
                        $this->ownerRecord->logs()->create($data);
                    }),
                
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}

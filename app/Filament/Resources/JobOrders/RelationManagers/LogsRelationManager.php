<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
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
use Filament\Tables\Filters\SelectFilter;

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
                SelectFilter::make('type')
                    ->name('type')
                    ->label('Log Type')
                    ->options([
                        'Parts' => 'Parts',
                        'Rejob' => 'Rejob',
                        'Bill' => 'Bill',
                        'Job Order' => 'Job Order',
                        'User-inputted' => 'User-inputted',
                    ])
                    ->query(function ($query, $state) {
                        switch ($state['value']) {
                            case 'Parts':
                                $query->where('details', 'like', '%Part%');
                                break;
                            case 'Rejob':
                                $query->where('details', 'like', '%Rejob%');
                                break;
                            case 'Bill':
                                $query->where('details', 'like', '%Bill%');
                                break;
                            case 'Job Order':
                                $query->where('details', 'like', '%Job Order%');
                                break;
                            case 'User-inputted':
                                $query->whereNot(function ($q) {
                                    $q->where('details', 'like', '%Part%')
                                      ->orWhere('details', 'like', '%Rejob%')
                                      ->orWhere('details', 'like', '%Bill%')
                                      ->orWhere('details', 'like', '%Job Order%');
                                });
                                break;
                        }
                        return $query;
                    }),
            ])
            ->headerActions([
                Action::make('add_log')
                    ->visible(fn() => Filament::auth()->user()->can('CreateLog:JobOrder') && $this->ownerRecord->status != 'Closed')
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
                EditAction::make()->visible(fn() => Filament::auth()->user()->can('EditLog:JobOrder')),
            ]);
    }
}

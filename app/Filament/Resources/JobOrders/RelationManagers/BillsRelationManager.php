<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Models\Bill;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';
    protected static ?string $recordTitleAttribute = 'bill_number';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')->label('Bill #')->sortable()->searchable(),
                TextColumn::make('total_amount')->label('Total')->money('php', true)->sortable(),
                TextColumn::make('amount_due')->label('Amount Due')->money('php', true)->sortable(),
                TextColumn::make('due_date')->label('Due Date')->date()->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Create Bill')
                    ->form([
                        DatePicker::make('due_date')->required(),
                        TextInput::make('total_amount')->numeric()->minValue(0)->step(0.01)->disabled(),
                        TextInput::make('amount_due')->numeric()->minValue(0)->step(0.01),
                    ])
                    ->action(function (array $data) {
                        // compute server-side to prevent tampering
                        $totalFromParts = $this->ownerRecord->parts()->sum(DB::raw('unit_price * quantity'));

                        $this->ownerRecord->bills()->create([
                            'total_amount' => $totalFromParts ?: ($data['total_amount'] ?? 0),
                            'amount_due'   => $data['amount_due'] ?? ($totalFromParts ?: 0),
                            'due_date'     => $data['due_date'],
                            'particulars'  => $data['particulars'] ?? null,
                            'status'       => 'Unpaid',
                            'bill_date'    => now(),
                            'bill_series'  => \App\Filament\Resources\JobOrders\Tables\JobOrdersTable::generateLastBillNumber()['bill_series'],
                            'bill_number'  => \App\Filament\Resources\JobOrders\Tables\JobOrdersTable::generateLastBillNumber()['bill_number'],
                        ]);

                        Notification::make()->title('Bill created')->success()->send();
                    }),
            ]);
    }
}
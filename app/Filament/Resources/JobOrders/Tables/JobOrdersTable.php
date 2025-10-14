<?php

namespace App\Filament\Resources\JobOrders\Tables;

use components;
use App\Models\Bill;
use Filament\Tables\Table;
use Filament\Actions\Action;
use GuzzleHttp\Promise\Create;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Bills\BillResource;


class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_number')
                    ->label('Job Order #')
                    ->searchable(),
                TextColumn::make('serviceType.service')
                    ->label('Service')
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
                TextColumn::make('date_target')
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
                Action::make('createBill')
                ->label('Create Bill')
                ->icon('heroicon-o-plus')
                ->button()
                ->modalHeading(fn ($record) => 'Create Bill for ' . $record->job_order_number)
                ->Schema([
                    TextInput::make('total_amount')
                        ->label('Total Amount')
                        ->numeric()
                        ->prefix('₱')
                        ->required(),

                    DatePicker::make('due_date')
                        ->label('Due Date')
                        ->required()
                        ->default(now()),

                    TextInput::make('particulars')
                        ->label('Particulars')
                        ->required(),
                ])
                ->action(function (array $data, $record) {
                    Bill::create([
                        'job_order_id' => $record->id, // link to job order
                        'total_amount' => $data['total_amount'],
                        'amount_due'   => $data['total_amount'],
                        'due_date'     => $data['due_date'],
                        'particulars'  => $data['particulars'],
                        'status'       => 'Pending',
                        'bill_date'    => now(),
                        'bill_series'  => JobOrdersTable::generateLastBillNumber()['bill_series'],
                        'bill_number'  => JobOrdersTable::generateLastBillNumber()['bill_number']
                    ]);

                    Notification::make()
                        ->title('Bill created successfully!')
                        ->success()
                        ->send();
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function generateLastBillNumber(): array
    {
        $bill = Bill::whereYear('bill_date', now()->year)->latest('bill_series')->first();
        $series = $bill?->bill_series + 1;
        return [
            'bill_series' => $series,
            'bill_number' => 'BILL#' . sprintf('%05d', $series)
        ];

    }
}

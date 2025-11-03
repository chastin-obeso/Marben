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
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Bills\BillResource;


class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                ->label('Open Job Order')
                ->button()
                ->icon('heroicon-o-eye'),
                Action::make('createBill')
                ->label('Create Bill')
                ->icon('heroicon-o-plus')
                ->button()
                ->modalHeading(fn ($record) => 'Create Bill for ' . $record->job_order_number)
                ->schema(fn ($record) => (function () use ($record) {
                    $totalFromParts = $record->parts()->sum(DB::raw('unit_price * quantity'));

                    return [
                        DatePicker::make('due_date')
                            ->label('Due Date')
                            ->required(),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->default($totalFromParts)
                            ->disabled(),

                        RichEditor::make('particulars')
                            ->label('Particulars')
                            ->nullable()
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                    ];
                })())
                ->action(function (array $data, $record) {
                    $totalFromParts = $record->parts()->sum(DB::raw('unit_price * quantity'));

                    Bill::create([
                        'job_order_id' => $record->id,
                        'total_amount' => $totalFromParts ?: ($data['total_amount'] ?? 0),
                        'amount_due'   => $totalFromParts ?: ($data['amount_due'] ?? 0),
                        'due_date'     => $data['due_date'],
                        'particulars'  => $data['particulars'] ?? null,
                        'status'       => 'Unpaid',
                        'bill_date'    => now(),
                        'bill_series'  => JobOrdersTable::generateLastBillNumber()['bill_series'],
                        'bill_number'  => JobOrdersTable::generateLastBillNumber()['bill_number'],
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

<?php

namespace App\Filament\Resources\JobOrders\Tables;

use components;
use App\Models\Bill;
use App\Models\JobOrder;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use GuzzleHttp\Promise\Create;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\Bills\BillResource;
use Filament\Schemas\Components\Utilities\Set;


class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date_target', 'asc')
            ->columns([
                TextColumn::make('job_order_number')
                    ->label('Job Order #')
                    ->searchable()
                    ->color(
                        fn ($record) => now()->toDateString() > $record->date_target && $record->status !== 'Completed' && $record->status !== 'Closed' ? 'danger' : 'success'
                    )
                    ->sortable(),
                TextColumn::make('serviceType.service')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Assigned Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->html(),
                TextColumn::make('date_requested')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_started')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_target')
                    ->label('Target Date')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_finished')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->color(fn($record) => match ($record->status) {
                                'Scheduled' => 'success',
                                'In Progress' => 'success',
                                'On Hold' => 'warning',
                                'Completed' => 'success',
                                'Cancelled' => 'danger',
                                'Closed' => 'success',
                                default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Scheduled' => 'Scheduled',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Closed' => 'Closed',
                        'Cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->relationship('serviceType', 'service'),
                TernaryFilter::make('is_rejob')
                    ->label('Rejobs')
                    ->trueLabel('Rejobs Only')
                    ->falseLabel('Without Rejobs')
                    ->default('')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('re_job_order_id'),
                        false: fn (Builder $query) => $query->whereNull('re_job_order_id'),
                ),
                Filter::make('Delayed')
                        ->label('Delayed')
                        ->query(fn ($query) =>
                            $query
                                ->whereDate('date_target', '<', now()->toDateString())
                                ->whereNotIn('status', ['Completed', 'Closed'])
                ),
                Filter::make('Hide Closed')
                        ->label('Hide Closed')
                        ->query(fn ($query) =>
                            $query
                                ->whereNotIn('status', ['Closed'])
                ),
                Filter::make('Hide Cancelled')
                        ->label('Hide Cancelled')
                        ->query(fn ($query) =>
                            $query
                                ->whereNotIn('status', ['Cancelled'])
                ),
            ])
            ->recordActions([
                ViewAction::make()
                ->visible()
                ->label('Open Job Order')
                ->button()
                ->icon('heroicon-o-eye'),
                Action::make('create_bill')
                ->disabled(fn ($record) => $record->unbilledJobOrderParts->isEmpty() && $record->service_fee_bill_id !== null)
                ->tooltip(fn ($record) => 
                    $record->unbilledJobOrderParts->isEmpty() && $record->service_fee_bill_id !== null ? 
                    'All parts have already been billed and service fee is already billed.' : ''
                )
                ->visible(fn ($record) => !in_array($record->status, ['Closed','Cancelled']) && Filament::auth()->user()->can('Create:Bill'))
                ->modalHeading(function ($record) {
                    return 'Add a New Bill Record for ' . $record->job_order_number;
                })
                ->button()
                ->schema([
                    TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->readOnly()
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₱'),
                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(now())
                    ->minDate(today()),
                Select::make('job_order_id')
                    ->label('Job Order')
                    ->options(function ($record) { 
                        return [$record->id => $record->job_order_number]; 
                    })
                    ->disablePlaceholderSelection()
                    ->dehydrated()
                    ->default(fn ($record) => $record->id)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateHydrated(function ($record, Set $set) {
                        if ($record) {
                            // 1. Fetch the unbilled parts using the model method
                            $parts = JobOrder::find($record->id)?->unbilledJobOrderParts;
                            $parts_array = $parts->toArray();
                            if(JobOrder::find($record->id)?->service_fee_bill_id == null){
                                $service_fee = JobOrder::find($record->id)?->service_fee;
                                $parts_array[] = [
                                    'name' => 'Service Fee',
                                    'unit_price' => 'N/A',
                                    'quantity' => 'N/A',
                                    'total_price' => $service_fee,
                                ];
                            }
                            // 2. Convert to an array and store this array in the form's data state
                            $set('parts_data', $parts_array);
                            $set('total_amount', collect($parts_array)->sum('total_price'));
                        } else {
                            $set('parts_data', []);
                        }
                    }),
                Section::make('Particulars of Unbilled Job Order Parts')
                    ->columnSpanFull()
                    ->components([
                        ViewField::make('parts_data')
                        ->live()
                        ->view('filament.infolists.entries.job-order-parts-table-2'),
                    ]),
                ])
                ->label('Create Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Add a New Bill Record')
                ->mutateDataUsing(function(array $data, $record) {
                    $data['status'] = 'Unpaid';
                    $data['amount_due'] = $data['total_amount'];
                    $data['bill_date'] = now();
                    $data['job_order_id'] = $record->id;
                    return array_merge($data, JobOrdersTable::generateLastBillNumber());
                })
                ->successNotification( // Use built-in success notification
                    Notification::make()
                        ->title('Bill Created Successfully')
                        ->success()
                )
                ->action(function(array $data) {
                    Bill::create([
                        'bill_number' => $data['bill_number'],
                        'bill_series' => $data['bill_series'],
                        'job_order_id' => $data['job_order_id'],
                        'bill_date' => $data['bill_date'],
                        'due_date' => $data['due_date'],
                        'total_amount' => $data['total_amount'],
                        'amount_paid' => 0,
                        'amount_due' => $data['total_amount'],
                        'status' => $data['status'],
                    ]);
                })
                ->after(function ($record, array $data) {
                        $jobOrderId = $data['job_order_id'];
        
                        // 1. Update the parent Job Order status
                        $jobOrder = JobOrder::find($jobOrderId);
                        if ($jobOrder) {
                            if ($jobOrder->service_fee_bill_id == null) {
                                $jobOrder->service_fee_bill_id = Bill::latest('id')->first()->id;
                            }
                            $jobOrder->save();
                        }
                        // dd($data);
                        // 2. Mark the individual unbilled Job Order Parts with the new Bill's ID
                        JobOrder::find($jobOrderId)
                            ->unbilledJobOrderParts()
                            ->update(['bill_id' => Bill::latest('id')->first()->id]);
                        
                        JobOrder::find($data['job_order_id'])->logs()->create([
                            'details' => 'Created Bill #' . $data['bill_number'],
                            'date' => now(),
                        ]);
                })
                ->closeModalByClickingAway(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn ($record) => [
                'bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800' => now()->toDateString() > $record->date_target 
                && $record->status !== 'Completed' 
                && $record->status !== 'Closed' 
                && $record->status !== 'Cancelled'
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

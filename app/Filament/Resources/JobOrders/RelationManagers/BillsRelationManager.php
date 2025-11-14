<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Models\Bill;
use App\Models\JobOrder;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

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
                Action::make('create_bill')
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
                        ->minDate(now()),
                    Select::make('job_order_id')
                        ->label('Job Order')
                        ->relationship('JobOrder', 'job_order_number')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                // 1. Fetch the unbilled parts using the model method
                                $parts = JobOrder::find($state)?->unbilledJobOrderParts;
                                $parts_array = $parts->toArray();
                                if(JobOrder::find($state)?->service_fee_status == 'Unbilled'){
                                    $service_fee = JobOrder::find($state)?->service_fee;
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
                            ->view('filament.infolists.entries.job-order-parts-table'),
                        ]),
                ])
                ->label('Add Bill')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Add a New Bill Record')
                ->mutateDataUsing(function(array $data) {
                    $data['status'] = 'Unpaid';
                    $data['amount_due'] = $data['total_amount'];
                    $data['bill_date'] = now();
                    return array_merge($data, $this->generateLastBillNumber());
                })
                
                ->after(function ($record, array $data) {
                        $jobOrderId = $data['job_order_id'];
        
                        // 1. Update the parent Job Order status
                        $jobOrder = JobOrder::find($jobOrderId);
                        if ($jobOrder) {
                            if ($jobOrder->service_fee_status == 'Unbilled') {
                                $jobOrder->service_fee_status = 'Billed';
                            }
                            $jobOrder->save();
                        }

                        // 2. Mark the individual unbilled Job Order Parts with the new Bill's ID
                        JobOrder::find($jobOrderId)
                            ->unbilledJobOrderParts()
                            ->update(['bill_id' => $record->id]);
                        
                        Notification::make()
                            ->title('Bill Created Successfully')
                            ->success()
                            ->send();
                })
                        
                        

                
                ->closeModalByClickingAway(false),
            ]);
    }
}
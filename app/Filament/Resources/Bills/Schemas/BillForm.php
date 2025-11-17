<?php

namespace App\Filament\Resources\Bills\Schemas;

use BcMath\Number;
use App\Models\Bill;
use Livewire\Component;
use App\Models\JobOrder;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Livewire;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\ServiceInvoices\Pages\CreateServiceInvoice;

class BillForm
{
    
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_amount')
                    ->hintIcon('heroicon-o-information-circle')
                    ->hintIconTooltip('This amount is automatically calculated based on the selected Job Order and its unbilled parts.')
                    ->label('Total Amount')
                    ->readOnly()
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₱'),
                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(today())
                    ->minDate(today()),
                Select::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('JobOrder', 'job_order_number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->options(function (Get $get) {
                        $billedJobOrderIds = Bill::pluck('job_order_id')->toArray();
                        return JobOrder::whereNotIn('id', $billedJobOrderIds)
                            ->where(function ($query) {
                                $query->whereHas('unbilledJobOrderParts')
                                      ->orWhereNull('service_fee_bill_id')
                                      ->whereNotIn('status', ['Cancelled', 'Closed']);
                            })
                            ->pluck('job_order_number', 'id');
                    })
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $parts = JobOrder::find($state)?->unbilledJobOrderParts;
                            $parts_array = $parts->toArray();
                            if(JobOrder::find($state)?->service_fee_bill_id == null){
                                $service_fee = JobOrder::find($state)?->service_fee;
                                $parts_array[] = [
                                    'name' => 'Service Fee',
                                    'unit_price' => 'N/A',
                                    'quantity' => 'N/A',
                                    'total_price' => $service_fee,
                                ];
                            }
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
            ]);
    }
}

<?php

namespace App\Filament\Resources\Bills\Schemas;

use components;
use App\Models\Bill;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use function Livewire\after;
use App\Models\ServiceInvoice;
use GuzzleHttp\Promise\Create;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Redirect;
use AnourValar\EloquentSerialize\Service;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use App\Filament\Resources\Bills\Tables\BillsTable;
use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;
use App\Filament\Resources\JobOrders\RelationManagers\ServiceInvoicesRelationManager;

class BillInfolist
{
    protected array $listeners = ['refresh-data' => '$refresh'];
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bill Information')
                    ->headerActions(
                        [
                            Action::make('createBill')
                            ->visible(fn ($record) => $record->status !== 'Refunded')
                            ->button()
                            ->label('Pay Bill')
                            ->icon('heroicon-o-plus')
                            ->color('primary')
                            ->modalHeading(fn($record) => 'Pay ' . $record->bill_number)
                            ->schema([
                                TextInput::make('amount_due')
                                    ->label('Amount Due')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false) // display-only, not saved
                                    ->default(fn ($record) => $record->amount_due)
                                    ->prefix('₱'),
                                TextInput::make('amount_paid')
                                    ->label('Amount Paid')
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->maxValue(fn (callable $get) => 
                                        $get ('amount_due')
                                    )
                                    ->prefix('₱'),
                                Select::make('payment_type')
                                    ->label('Payment Type')
                                    ->options([
                                        'Cash' => 'Cash',
                                        'GCash' => 'GCash',
                                    ])
                                    ->preload()
                                    ->reactive()
                                    ->required(),
                                TextInput::make('reference_number')
                                    ->label('Reference Number')
                                    ->maxLength(255)
                                    ->required()
                                    ->hidden(fn (callable $get) => $get('payment_type') != 'GCash'),
                                    ]
                                )
                            ->action(function (array $data, Bill $record, $livewire) {
                                    $invoice = ServiceInvoice::create([
                                            'service_invoice_number' => BillsTable::generateLastServiceInvoiceNumber()['service_invoice_number'],
                                            'service_invoice_series' => BillsTable::generateLastServiceInvoiceNumber()['service_invoice_series'],
                                            'amount_paid'            => $data['amount_paid'],
                                            'payment_type'           => $data['payment_type'],
                                            'payment_date'           => now(),
                                            'bill_id'                => $record->id,
                                            'reference_number'       => $data['reference_number'] ?? null,
                                    ]);
                            
                                    $bill = $invoice->bill;
                                            if ($bill) {
                                                $bill->amount_due -= $invoice->amount_paid;
                                                $bill->save();
                                            }
                                    $invoice->bill->updatePaymentStatus($bill->jobOrder); 
                                    
                                    Notification::make()
                                        ->title('Bill Paid Successfully')
                                        ->success()
                                        ->send();
                                    return Redirect::to(route('filament.admin.resources.bills.index'));
                                }
                            
                            )
                            ->closeModalByClickingAway(false)

                           
                        ]
                    )
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('bill_date')->date(),
                        TextEntry::make('due_date')->date(),
                        TextEntry::make('total_amount')->money('PHP', true),
                        TextEntry::make('amount_due')->money('PHP', true),
                        TextEntry::make('status'),
                        TextEntry::make('JobOrder.job_order_number')->label('Job Order #'),
                        TextEntry::make('particulars')->html(),
                    ]),
                    Section::make('Service Invoices')
                        ->columnSpanFull()
                        ->components([
                            ViewEntry::make('service_invoices')
                            ->view('filament.infolists.entries.service-invoices-table'),
                        ]),
                    Section::make('Particulars')
                        ->columnSpanFull()
                        ->components([
                            ViewField::make('parts_data')
                            ->live()
                            ->view('filament.infolists.entries.job-order-parts-table-3'),
                        ]),
                ]);
    }
}

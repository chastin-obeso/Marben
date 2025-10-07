<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class JobOrderForm
{
    public static function configure(Schema $schema, $isFullWidthCustomer = false): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name'),
                        TextInput::make('email'),
                        TextInput::make('phone'),
                    ])
                    ->editOptionForm([
                        TextInput::make('name'),
                        TextInput::make('email'),
                        TextInput::make('phone'),
                    ])
                    ->createOptionAction(function(Action $action) {
                        $action->modalWidth('md')
                            ->modalHeading('New Customer');
                    })
                    ->editOptionAction(function(Action $action) {
                        $action->modalWidth('md')
                            ->modalHeading('Edit Customer Details');
                    })
                    ->columnSpan(fn() => $isFullWidthCustomer ? 2 : 1),
                Section::make('Select a Service')
                    ->description('Details/Specific Instructions')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('date_requested')
                            ->label('Appointment Date')
                            ->default(now())
                            ,
                        Select::make('service_type')
                            ->relationship('serviceType', 'service')
                            ->createOptionForm([
                                TextInput::make('service')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->editOptionForm([
                                TextInput::make('service')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        Select::make('user_id')
                            ->label('Employee')
                            ->options(fn(Get $get) => User::whereRelation('serviceTypes', 'service', $get('service_type'))->pluck('name', 'id'))
                            ->searchable()
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->columnSpanFull()
                    ])
                    ->extraAttributes([
                        'class' => 'shadow-lg'
                    ])
                    ->columns(2),
            ]);

            
    }
}

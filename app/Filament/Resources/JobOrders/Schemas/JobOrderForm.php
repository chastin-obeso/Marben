<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Utilities\Get;

class JobOrderForm
{
    public static function configure(Schema $schema, $isFullWidthCustomer = false): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->getOptionLabelFromRecordUsing(function (Model $record): string {
                        return "{$record->name} ({$record->email})";
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Customer::where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->get()
                            ->mapWithKeys(fn ($record) => [$record->id => "{$record->name} ({$record->email})"])
                            ->toArray();
                    })
                    ->preload()
           
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique()->required()->email(),
                        TextInput::make('phone')
                            ->label('Phone number (09XXXXXXXXX)')
                            ->tel()
                            ->regex('/^09[0-9]{9}$/')
                            ->validationMessages([
                                'regex' => 'The phone number must start with 09 and be exactly 11 digits long.',
                            ]),
                    ])
                    ->editOptionForm([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique()->required()->email(),
                        TextInput::make('phone')
                            ->label('Phone number (09XXXXXXXXX)')
                            ->tel()
                            ->regex('/^09[0-9]{9}$/')
                            ->validationMessages([
                                'regex' => 'The phone number must start with 09 and be exactly 11 digits long.',
                        ]),
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
                        DatePicker::make('date_target')
                            ->label('Target Date')
                            ->required()
                            ->minDate(now()),
                        Select::make('service_type_id')
                            ->relationship('serviceType', 'service')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('user_id')
                            ->label('Employee')
                            ->options(function (Get $get) {
                                $selected = $get('service_type_id');

                                if (empty($selected)) {
                                    return User::pluck('name', 'id');
                                }

                                return User::whereHas('serviceTypes', function ($q) use ($selected) {
                                    $q->where('service_types.id', $selected);
                                })->pluck('name', 'id');
                            })
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

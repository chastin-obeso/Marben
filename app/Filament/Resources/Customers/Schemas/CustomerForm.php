<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique()
                    ->required(),
                TextInput::make('phone')
                    ->required()
                    ->label('Phone number (09XXXXXXXXX)')
                    ->tel()
                    ->regex('/^09[0-9]{9}$/')
                    ->validationMessages([
                        'regex' => 'The phone number must start with 09 and be exactly 11 digits long.',
                    ]),
            ]);
    }
}

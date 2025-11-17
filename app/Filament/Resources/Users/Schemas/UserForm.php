<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\CheckboxList;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        Fieldset::make('Account Info')
                        ->columnSpanFull()
                        ->columns([
                            'default' => 2,
                            'sm' => 2
                        ])
                        ->schema([
                            TextInput::make('username'),
                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->unique()
                                ->required(),
                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->revealable(),
                            TextInput::make('phone')
                            ->label('Phone number (09XXXXXXXXX)')
                            ->tel()
                            ->regex('/^09[0-9]{9}$/')
                            ->validationMessages([
                                'regex' => 'The phone number must start with 09 and be exactly 11 digits long.',
                            ]),
                            Select::make('role')
                                ->required()
                                ->visible(fn () => Filament::auth()->user()->can('Assign:User'))
                                ->label('Role')
                                ->relationship('roles', 'name', fn($query) => $query->where('name', '!=', 'super_admin'))
                                ->preload()
                                ->searchable(),
                            Select::make('service_type')
                                ->label('Services offered')
                                ->multiple()
                                ->relationship('serviceTypes', 'service')
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('service')
                                ]),
                        ]),
                        
                        
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'shadow-lg'
                    ])
            ]);
    }
}

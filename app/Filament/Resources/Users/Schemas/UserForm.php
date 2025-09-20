<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
                                ->required(),
                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->revealable(),
                            TextInput::make('phone'),
                            CheckboxList::make('roles')
                                ->columnSpanFull()
                                ->relationship('roles', 'name')
                                ->searchable(),
                        ]),
                        Select::make('service_type')
                            ->multiple()
                            ->relationship('serviceTypes', 'service')
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('service')
                            ]),
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'shadow-lg'
                    ])
            ]);
    }
}

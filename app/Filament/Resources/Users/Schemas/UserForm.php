<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;

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
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'shadow-lg'
                    ])
            ]);
    }
}

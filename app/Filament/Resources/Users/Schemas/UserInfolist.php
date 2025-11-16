<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('username'),
                TextEntry::make('phone'),
                TextEntry::make('status')
                    ->formatStateUsing(fn($state, $record = null) => ((int) $state) ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn($state) => ((int) $state) ? 'success' : 'danger'),
                TextEntry::make('roles.name')
                    ->label('Role'),
                TextEntry::make('serviceTypes.service')
                    ->label('Services Offered'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}

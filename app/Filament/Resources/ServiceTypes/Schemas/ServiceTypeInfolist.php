<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

use BcMath\Number;
use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Marben\app\Models\ServiceType;

class ServiceTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('service')->label('Service'),
                TextEntry::make('job_orders_count')
                    ->label('No. of Job Orders')
                    ->numeric()
                    ->state(function ($record) {
                        return $record->job_orders->count();
                    }),
            ]);
    }
}

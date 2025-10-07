<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

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

class ServiceTypeForm
{
     public static function configure(Schema $schema, $isFullWidthCustomer = false): Schema
    {
        return $schema
            ->components([
                TextInput::make('service')
                    ->required()
                    ->columnSpanFull(),
                
            ]);

            
    }
}

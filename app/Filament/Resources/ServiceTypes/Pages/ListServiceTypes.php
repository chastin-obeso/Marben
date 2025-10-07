<?php

namespace App\Filament\Resources\ServiceTypes\Pages;

use App\Models\ServiceType;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ServiceTypes\ServiceTypeResource;

class ListServiceTypes extends ListRecords
{
    protected static string $resource = ServiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modal()
                ->modalHeading('New Service Type')
                ->closeModalByClickingAway(false),
        ];
    }

}

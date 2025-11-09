<?php

namespace App\Filament\Resources\ServiceTypes\Pages;

use App\Filament\Resources\ServiceTypes\ServiceTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use GuzzleHttp\Psr7\Query;

class ViewServiceType extends ViewRecord
{
    protected static string $resource = ServiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function viewQuery($query): Query
    {
        return $query->withCount('job_orders');
    }
}

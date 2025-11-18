<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Roles\RoleResource;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}

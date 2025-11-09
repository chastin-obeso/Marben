<?php

namespace App\Filament\Resources\ServiceTypes;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use App\Models\ServiceType;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\ServiceTypes\Pages\EditServiceType;
use App\Filament\Resources\ServiceTypes\Pages\ViewServiceType;
use App\Filament\Resources\ServiceTypes\Pages\ListServiceTypes;
use App\Filament\Resources\ServiceTypes\Pages\CreateServiceType;
use App\Filament\Resources\ServiceTypes\Schemas\ServiceTypeForm;
use App\Filament\Resources\ServiceTypes\Tables\ServiceTypesTable;
use App\Filament\Resources\ServiceTypes\Schemas\ServiceTypeInfolist;
use App\Filament\Resources\ServiceTypes\RelationManagers\JobOrdersRelationManager;

class ServiceTypeResource extends Resource
{
    protected static ?string $model = ServiceType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Job Order Management';

    protected static ?string $recordTitleAttribute = 'service';

    public static function form(Schema $schema): Schema
    {
        return ServiceTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceTypeInfolist::configure($schema);
    }       

    public static function table(Table $table): Table
    {
        return ServiceTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            JobOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceTypes::route('/'),
            // 'create' => CreateServiceType::route('/create'),
            'edit' => EditServiceType::route('/{record}/edit'),
            'view' => ViewServiceType::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Resources\JobOrders;

use App\Filament\Resources\JobOrders\Pages\CreateJobOrder;
use App\Filament\Resources\JobOrders\Pages\EditJobOrder;
use App\Filament\Resources\JobOrders\Pages\ListJobOrders;
use App\Filament\Resources\JobOrders\Pages\ViewJobOrder;
use App\Filament\Resources\JobOrders\Schemas\JobOrderForm;
use App\Filament\Resources\JobOrders\Schemas\JobOrderInfolist;
use App\Filament\Resources\JobOrders\Tables\JobOrdersTable;
use App\Models\JobOrder;
use BackedEnum;
use UnitEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobOrderResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Job Order Management';

    protected static ?string $recordTitleAttribute = 'job_order_number';

    public static function form(Schema $schema): Schema
    {
        return JobOrderForm::configure($schema, isFullWidthCustomer: true);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobOrdersTable::configure($table);
    }

    public static function getTabs(): array {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('is_archived', false)),
            'archived' => Tab::make('Archived')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('is_archived', true)),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogsRelationManager::class,
            RelationManagers\PartsRelationManager::class,
            // RelationManagers\CustomersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobOrders::route('/'),
            // 'create' => CreateJobOrder::route('/create'),
            'view' => ViewJobOrder::route('/{record}'),
            // 'edit' => EditJobOrder::route('/{record}/edit'),
        ];
    }
}

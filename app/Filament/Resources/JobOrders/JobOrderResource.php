<?php

namespace App\Filament\Resources\JobOrders;

use UnitEnum;
use BackedEnum;
use App\Models\JobOrder;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\JobOrders\Pages\EditJobOrder;
use App\Filament\Resources\JobOrders\Pages\ViewJobOrder;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\JobOrders\Pages\ListJobOrders;
use App\Filament\Resources\JobOrders\Pages\CreateJobOrder;
use App\Filament\Resources\JobOrders\Schemas\JobOrderForm;
use App\Filament\Resources\JobOrders\Tables\JobOrdersTable;
use App\Filament\Resources\JobOrders\Schemas\JobOrderInfolist;
use Illuminate\Support\Facades\Auth;

class JobOrderResource extends Resource
{
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = Auth::user();

        if ($user && $user->can('ViewAssigned:JobOrder') && !$user->can('ViewAny:JobOrder')) {
            return $query->where('user_id', $user->id);
        }


        return $query;
    }
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
            RelationGroup::make('Contacts',[
                RelationManagers\PartsRelationManager::class,
                RelationManagers\LogsRelationManager::class,
                RelationManagers\BillsRelationManager::class,
                RelationManagers\RejobRelationManager::class,
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobOrders::route('/'),
            'edit' => EditJobOrder::route('/{record}/edit'),
            'view' => ViewJobOrder::route('/{record}'),
        ];
    }
}

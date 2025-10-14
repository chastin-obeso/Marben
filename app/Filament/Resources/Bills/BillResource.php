<?php

namespace App\Filament\Resources\Bills;

use UnitEnum;
use BackedEnum;
use App\Models\Bill;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use RelationManagers\JobOrdersRelationManager;
use App\Filament\Resources\Bills\Pages\EditBill;
use App\Filament\Resources\Bills\Pages\ViewBill;
use App\Filament\Resources\Bills\Pages\ListBills;
use App\Filament\Resources\Bills\Pages\CreateBill;
use App\Filament\Resources\Bills\Schemas\BillForm;
use App\Filament\Resources\Bills\Tables\BillsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RelationManagers\ServiceInvoicesRelationManager;
use App\Filament\Resources\Bills\Schemas\BillInfolist;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Bills\Pages\ListJobOrdersUnderBills;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Billing and Payments';

    protected static ?string $recordTitleAttribute = 'bill_number';

    public static function form(Schema $schema): Schema
    {
        return BillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBills::route('/'),
            'create' => CreateBill::route('/create'),
            'view' => ViewBill::route('/{record}'),
            'edit' => EditBill::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getSchema()
    {
        return (new Schema())
            ->components([
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₱'),
                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(now())
                    ->minDate(now()),
                TextInput::make('particulars')
                    ->label('Particulars')
                    ->required()
                    ->maxLength(255),
                Select::make('job_order_id')
                    ->label('Job Order')
                    ->relationship('JobOrder', 'job_order_number')
                    ->searchable()
                    ->preload()
                    ->required(),
            
            ]);
    }
}

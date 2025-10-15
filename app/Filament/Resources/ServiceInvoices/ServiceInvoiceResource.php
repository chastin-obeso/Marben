<?php

namespace App\Filament\Resources\ServiceInvoices;

use App\Filament\Resources\ServiceInvoices\Pages\CreateServiceInvoice;
use App\Filament\Resources\ServiceInvoices\Pages\EditServiceInvoice;
use App\Filament\Resources\ServiceInvoices\Pages\ListServiceInvoices;
use App\Filament\Resources\ServiceInvoices\Pages\ViewServiceInvoice;
use App\Filament\Resources\ServiceInvoices\Schemas\ServiceInvoiceForm;
use App\Filament\Resources\ServiceInvoices\Schemas\ServiceInvoiceInfolist;
use App\Filament\Resources\ServiceInvoices\Tables\ServiceInvoicesTable;
use App\Models\ServiceInvoice;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceInvoiceResource extends Resource
{
    protected static ?string $model = ServiceInvoice::class;

    protected static string|BackedEnum|null $navigationGroupIcon = Heroicon::DocumentCurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Billing and Payments';

    public static function form(Schema $schema): Schema
    {
        return ServiceInvoiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceInvoiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceInvoicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceInvoices::route('/'),
            // 'create' => CreateServiceInvoice::route('/create'),
            'view' => ViewServiceInvoice::route('/{record}'),
            'edit' => EditServiceInvoice::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    

    
}

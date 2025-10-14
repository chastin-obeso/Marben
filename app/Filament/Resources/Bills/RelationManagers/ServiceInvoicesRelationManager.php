<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ServiceInvoices\ServiceInvoiceResource;

class ServiceInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'service_invoices';

    protected static ?string $relatedResource = ServiceInvoiceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

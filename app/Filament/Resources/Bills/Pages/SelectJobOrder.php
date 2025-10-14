<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use App\Models\JobOrder;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class SelectJobOrder extends ListRecords
{
    // Bind this page to the Bills resource so it lives under /resources/bills
    protected static string $resource = BillResource::class;

    protected static ?string $title = 'Select Job Order';

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('job_order_number')->label('Job Order #')->searchable(),
            TextColumn::make('customer.name')->label('Customer')->searchable(),
            TextColumn::make('date_requested')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('open_bills')
                ->label('Open Bills')
                ->url(fn (JobOrder $record) => BillResource::getUrl('create', ['job_order_id' => $record->id])),
        ];
    }

    protected function getEloquentQuery(): Builder
    {
        return JobOrder::query();
            // ->with('customer')
            // ->withCount('bills')
            // ->orderBy('bills_count', 'asc');
    }
}
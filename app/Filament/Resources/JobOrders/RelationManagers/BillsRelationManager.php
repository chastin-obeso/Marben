<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use App\Models\Bill;
use App\Models\JobOrder;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';
    protected static ?string $recordTitleAttribute = 'bill_number';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')->label('Bill #')->sortable()->searchable(),
                TextColumn::make('total_amount')->label('Total')->money('php', true)->sortable(),
                TextColumn::make('amount_due')->label('Amount Due')->money('php', true)->sortable(),
                TextColumn::make('due_date')->label('Due Date')->date()->sortable(),
                TextColumn::make('status')->label('Status')->sortable()->color(function ($record) {
                    if ($record->status === 'Fully Paid') {
                        return 'success';
                    } elseif ($record->status === 'Unpaid') {
                        return 'danger';
                    } elseif ($record->status === 'Partially Paid') {
                        return 'warning';
                    }
                }),
            ]);

    }
}
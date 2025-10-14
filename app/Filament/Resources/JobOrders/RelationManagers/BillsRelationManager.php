<?php

namespace App\Filament\Resources\JobOrders\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Bills\BillResource;
use Filament\Resources\RelationManagers\RelationManager;

class BillsRelationManager extends RelationManager
{
    
    protected static string $relationship = 'bills';

    protected static ?string $relatedResource = BillResource::class;

    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')->required(),
                TextInput::make('description'),
                // ... other Bill fields
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('created_at')->date(),
            ])
            ->headerActions([
                CreateAction::make(), 
            ]);
    }
}

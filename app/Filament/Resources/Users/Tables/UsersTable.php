<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
    use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->color(
                        fn ($record) => $record->status === 1 ? 'success' : 'danger'
                    )
                    ->label('Email address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->toggleable()
                    ->toggledHiddenByDefault(false)
                    ->searchable()
                    ->state(fn ($record) => $record->phone ?? 'N/A'),
                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(false),
                ToggleColumn::make('status')
                    ->visible(Auth::user()->can('ChangeStatus:User'))
                    ->label('Active')
                    ->onColor(function(User $user) {
                        if (auth()->user()->id === $user->id || $user->role === '1') {
                            return 'gray';
                        }
                    })
                    ->offColor(function(User $user) {
                        if (auth()->user()->id === $user->id || $user->role === '1') {
                            return 'gray';
                        }
                    })
                    ->disabled(function (User $user) {
                        if (auth()->user()->id === $user->id) {
                            return true;
                        }
                        if ($user->role === '1' && auth()->user()->role !== '1') {
                            return true;
                        }
                        return false;
                    })
                    ->tooltip(function(User $user){
                        if(auth()->user()->id === $user->id){
                            return 'Cannot toggle own status!';
                        }
                        if($user->role === '1' && auth()->user()->role !== '1'){
                            return 'Cannot change status of Super Admin!';
                        }
                    })
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->searchable(),
                TextColumn::make('serviceTypes.service')
                    ->toggleable()
                    ->toggledHiddenByDefault(false)
                    ->label('Services Offered')
                    ->getStateUsing(function ($record) {
                        $services = $record->serviceTypes->pluck('service')->toArray();
                        
                        $count = count($services);
                        if ($count > 3) {
                            $firstThree = array_slice($services, 0, 3);
                            return implode(', ', $firstThree) . '...';
                        }

                        return implode(', ', $services);
                    }),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->badge()
                    ->label('Role')
                    ->toggledHiddenByDefault(false)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('status_filter')
                    ->label('Status')
                    ->schema([
                        Radio::make('status')
                            ->inline()
                            ->options([
                                '' => 'All',
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ])
                            ->default(''),
                    ])
                    ->query(fn ($query, array $data) => 
                    isset($data['status']) && $data['status'] !== '' ? 
                    $query->where('status', (bool) $data['status']) : $query),
                SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->relationship('serviceTypes', 'service'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                ->disabled(function (User $user) {
                        if ($user->role === '1' && auth()->user()->role !== '1') {
                            return true;
                        }
                        return false;
                    })
                ->tooltip(function(User $user){
                        if($user->role === '1' && auth()->user()->role !== '1'){
                            return 'Cannot edit Super Admin!';
                        }
                    }),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}

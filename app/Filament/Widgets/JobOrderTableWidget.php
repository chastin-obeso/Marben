<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JobOrders\JobOrderResource;

class JobOrderTableWidget extends TableWidget
{

    public $start;
    public $end;

    #[On('calendarRangeUpdated')]
    public function setCalendarRange($start, $end): void
    {
        $this->start = $start;
        $this->end = $end;
        $this->resetTable();
    }


    

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date_target', 'asc')
            ->query(function (): Builder {
                $user = Auth::user();
                if ($user && $user->can('ViewAssigned:JobOrder') && !$user->can('ViewAny:JobOrder')) {
                    return JobOrder::query()->where('user_id', $user->id)->dateBetween([$this->start, $this->end]);
                }
                return JobOrder::query()->dateBetween([$this->start, $this->end]); 
            })
            ->columns([
                Stack::make([
                    TextColumn::make('job_order_number')
                        ->color(fn ($record) => now()->toDateString() > $record->date_target && $record->status !== 'Completed' && $record->status !== 'Closed' ? 'danger' : 'success')
                        ->description(fn($record) => "Target Date: ". Carbon::parse($record->date_target)->format('F d, Y'))
                        ->weight('bold')
                        ->url(fn ($record): string => JobOrderResource::getUrl('view', ['record' => $record]))
                    // TextColumn::make('description')
                    //     ->html()
                    //     ->color('gray-1'),
                ])
            ])
            ->filters([
                
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

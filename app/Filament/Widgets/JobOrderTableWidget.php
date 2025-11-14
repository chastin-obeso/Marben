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
                        ->description(fn($record) => "Target Date: ". Carbon::parse($record->date_target)->format('F d, Y'))
                        ->weight('bold'),
                    TextColumn::make('description')
                        ->html()
                        ->color('gray-1'),
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

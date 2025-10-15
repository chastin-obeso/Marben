<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
            ->query(fn (): Builder => JobOrder::query()->dateBetween([$this->start, $this->end]))
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

<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Guava\Calendar\Enums\Context;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\Filament\Actions\CreateAction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Calendar extends CalendarWidget
{
    // protected string $view = 'filament.widgets.calendar';

    // protected function getEvents(FetchInfo $info): Collection | array | Builder {}

    // protected bool $dateSelectEnabled = true;

    protected bool $dateClickEnabled = true;

    protected bool $eventClickEnabled = true;

    public function getHeading(): string|HtmlString
    {
        return  new HtmlString('<div>Calendar</div>');
    }

    protected bool $dayMaxEvents = true;

    public function createJobOrderAction(): CreateAction
    {
        return $this->createAction(JobOrder::class)
                    ->fillForm(fn(ContextualInfo $info) => [
                        'date_target' => $info->date->toDateString()
                    ])
                    ->mutateDataUsing(function($data) {
                        $data['status'] = 'Pending';
                        
                        // dd(array_merge($data, $this->generateLastJobOrderNumber()));
                        return array_merge($data, $this->generateLastJobOrderNumber());
                    });
    }

    public function onDateClick(DateClickInfo $info): void {
        $this->mountAction('createJobOrder');
    }

    public function generateLastJobOrderNumber(): array
    {
        $job_order = JobOrder::whereYear('date_requested', now()->year)->latest('series')->first();
        $series = $job_order?->series + 1;
        return [
            'series' => $series,
            'job_order_number' => '#JO' . sprintf('%05d', $series)
        ];

    }

    
    protected function getEvents(FetchInfo $info): Collection|array|EloquentBuilder
    {
        // dd(JobOrder::whereBetween('date_requested', [$info->start->toDateString(), $info->end->toDateString()])
        //         ->get());

        $this->dispatch('calendarRangeUpdated', 
            start: $info->start->toDateString(), 
            end: $info->end->toDateString()
        );
        
        $user = Auth::user();
                if ($user && $user->can('ViewAssigned:JobOrder') && !$user->can('ViewAny:JobOrder')) {
                    $query = JobOrder::query()->where('user_id', $user->id);
                }else{
                    $query = JobOrder::query();
                }

        $data = $query->get();
        
        return $data
                ->map(fn (JobOrder $job) => CalendarEvent::make($job)
                                                ->title($job->job_order_number)
                                                ->start($job->date_target)
                                                ->end($job->date_target)
                                                ->allDay()
                                                ->model($job::class)
                                                ->action('view')
                );
    }

}

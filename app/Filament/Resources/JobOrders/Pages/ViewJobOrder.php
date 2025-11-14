<?php

namespace App\Filament\Resources\JobOrders\Pages;

use App\Models\JobOrder;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\JobOrders\JobOrderResource;

class ViewJobOrder extends ViewRecord
{
    protected static string $resource = JobOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->visible(fn () => in_array($this->record->status, ['Scheduled', 'In Progress', 'On Hold']))
            ->button()
            ->outlined(),
            Action::make('start_job_order')
                ->visible(fn () => $this->record->status === 'Scheduled')
                ->button()
                ->label('Start Job Order')
                ->color('primary')
                ->action(function () {
                    $this->record->status = 'In Progress';
                    $this->record->date_started = now();
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order Started',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order started!')
                        ->success()
                        ->send();
                }),
            Action::make('hold_job_order')
                ->visible(fn () => $this->record->status === 'In Progress')
                ->button()
                ->label('Hold Job Order')
                ->color('danger')
                ->action(function () {
                    $this->record->status = 'On Hold';
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order On Hold',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order On Hold!')
                        ->danger()
                        ->send();
                }),
            Action::make('complete_job_order')
                ->visible(fn () => $this->record->status === 'In Progress')
                ->button()
                ->label('Complete Job Order')
                ->color('primary')
                ->action(function () {
                    $this->record->status = 'Completed';
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order Completed',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order Completed!')
                        ->success()
                        ->send();
                }),    
            Action::make('resume_job_order')
                ->visible(fn () => $this->record->status === 'On Hold')
                ->button()
                ->label('Resume Job Order')
                ->color('primary')
                ->action(function () {
                    $this->record->status = 'In Progress';
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order Resumed',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order resumed!')
                        ->success()
                        ->send();
                }),
            Action::make('close_job_order')
           
                ->visible(fn () => $this->record->status === 'Completed')
                ->disabled(function (Model $record) {
                    if (!$record->bills()->exists()) {
                        return true;
                    }
                    return $record->bills()
                        ->where('amount_due', '>', 0)
                        ->exists();
                })
                ->tooltip(function (Model $record) {
                    if ($record->bills()->exists()) {
                        if ($record->bills()
                            ->where('amount_due', '>', 0)
                            ->exists()) {
                            return 'Cannot close job order with outstanding bills.';
                        }
                    }
                    return 'Cannot close unbilled job order.';
                })
                ->button()
                ->label('Close Job Order')
                ->color('primary')
                ->action(function () {
                    $this->record->status = 'Closed';
                    $this->record->date_finished = now();
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order Closed',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order Closed!')
                        ->success()
                        ->send();
                }),  
            Action::make('cancel_job_order')
                ->visible(fn () => in_array($this->record->status, ['Scheduled', 'In Progress', 'On Hold']))
                ->button()
                ->label('Cancel Job Order')
                ->color('danger')
                ->action(function () {
                    $this->record->status = 'Cancelled';
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Job Order Cancelled',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order Cancelled!')
                        ->success()
                        ->send();
                    
                }),  
            Action::make('rejob')
                ->visible(fn () => $this->record->status === 'Closed')
                ->disabled(function (Model $record) {
                    // dd($record->reJobOrder()->where(function ($query) {$query->where('status', 'closed')->orWhere('status', 'completed');})->exists());
                    if ($record->reJobOrder()->where(function ($query) {$query->where('status', 'closed')->orWhere('status', 'completed');})->exists()) {
                        return true;
                    }
                    return false;
                })
                ->tooltip(function (Model $record) {
                    if (!$record->reJobOrder()->where('status', 'closed')->orWhere('status', 'completed')->exists()) {
                        return 'Rejob already created for this job order.';
                    }
                    return 'Create a rejob from this job order.';
                })
                ->button()
                ->label('Rejob')
                ->color('danger')
                ->modalHeading(fn () => 'Create Rejob from ' . $this->record->job_order_number)
                ->form(fn () => [
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->default($this->record->customer_id)
                        ->required(),
                    Select::make('service_type_id')
                        ->label('Service Type')
                        ->relationship('serviceType', 'service')
                        ->default($this->record->service_type_id ?? $this->record->service_type)
                        ->required(),
                    Select::make('user_id')
                        ->label('Assigned To')
                        ->relationship('user', 'name')
                        ->default($this->record->user_id)
                        ->required(),
                    DatePicker::make('date_target')
                        ->label('Target Date')
                        ->default($this->record->date_target),
                    RichEditor::make('description')
                        ->label('Description')
                        ->default($this->record->description),
                    Checkbox::make('copy_parts')
                        ->label('Copy parts from original')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $last = JobOrder::whereYear('date_requested', now()->year)->latest('series')->first();
                    $series = $last?->series + 1;
                    $jobNumber = 'JO#' . sprintf('%05d', $series);

                    $new = JobOrder::create([
                        'customer_id'      => $data['customer_id'],
                        'service_type_id'  => $data['service_type_id'] ?? null,
                        'user_id'          => $data['user_id'],
                        'date_target'      => $data['date_target'] ?? now(),
                        'description'      => $data['description'] ?? null,
                        'status'           => 'Scheduled',
                        'date_requested'   => now(),
                        'series'           => $series,
                        'job_order_number' => $jobNumber,
                        're_job_order_id'  => $this->record->id,
                    ]);

                    if (!empty($data['copy_parts'])) {
                        foreach ($this->record->parts as $part) {
                            $new->parts()->create($part->only(['name','unit_price','quantity','total_price','status']));
                        }
                    }

                    


                    $this->record->logs()->create([
                        'details' => 'Rejob created: ' . $new->job_order_number,
                        'date' => now(),
                    ]);
                    $new->logs()->create([
                        'details' => 'Created by rejob from ' . $this->record->job_order_number,
                        'date' => now(),
                    ]);

                    Notification::make()
                        ->title('New Job Order created: ' . $new->job_order_number)
                        ->success()
                        ->send();

                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $new]));

                    
                }),
        ];
    }
}

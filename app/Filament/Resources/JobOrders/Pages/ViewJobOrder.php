<?php

namespace App\Filament\Resources\JobOrders\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
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
                ->disabled(function () {
                    return $this->record->bills()
                        ->where('amount_due', '>', 0)
                        ->exists();
                })
                ->tooltip('Cannot close job order with outstanding bills.')
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
                ->button()
                ->label('Rejob')
                ->color('danger')
                ->action(function () {
                    $this->record->status = 'In Progress';
                    $this->record->save();
                    $this->redirect(JobOrderResource::getUrl('view', ['record' => $this->record]));
                    $this->record->logs()->create([
                        'details' => 'Rejob Initiated',
                        'date' => now(),
                    ]);
                    Notification::make()
                        ->title('Job Order resumed!')
                        ->success()
                        ->send();
                }),  
        ];
    }
}

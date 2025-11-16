<x-filament-panels::page>
    <div style="display: flex; gap: 1.25rem; flex-direction: column;">
        <div class="flex-1">@livewire(\App\Filament\Widgets\Stats::class)</div>
        <div style="display: flex; flex-direction: row; gap: 1.25rem;">
            <div class="flex-1">@livewire(\App\Filament\Widgets\SalesReportChart::class)</div>
            <div class="flex-1">@livewire(\App\Filament\Widgets\ServiceTypeDonut::class)</div>
        </div>
        
    </div>
</x-filament-panels::page>

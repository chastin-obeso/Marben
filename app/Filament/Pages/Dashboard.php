<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use App\Filament\Widgets\Calendar;
use Filament\Pages\Dashboard as BasePage;
use App\Filament\Widgets\JobOrderTableWidget;

class Dashboard extends BasePage
{

    // protected string $view = 'filament.pages.dashboard';

    // protected ?string $heading = 'Home';

    // protected static ?string $navigationLabel = 'Home';

    public function getColumns(): int | array
    {
        return 2;
    }
    public function mount()
    {
        $panel = Filament::getCurrentPanel();
        $panel->sidebarCollapsibleOnDesktop()->sidebarFullyCollapsibleOnDesktop();
    }

    public function getWidgets(): array
    {
        return [
            Calendar::class,
            JobOrderTableWidget::class,
            
            
        ];
    }

  

}

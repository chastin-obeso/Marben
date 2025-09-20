<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Page;

class Dashboard extends BasePage
{

    protected string $view = 'filament.pages.dashboard';

    protected ?string $heading = 'Home';

    protected static ?string $navigationLabel = 'Home';

    public function mount()
    {
        $panel = Filament::getCurrentPanel();

        $panel->sidebarCollapsibleOnDesktop()->sidebarFullyCollapsibleOnDesktop();
    }

}

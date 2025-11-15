<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SalesReport extends Page
{
    protected static ?string $title = 'Sales Report';
    protected ?string $heading = 'Sales Report';
    // This makes the page appear in the navbar
    protected static ?string $navigationLabel = 'Sales Report';

    // Optionally, choose an icon
    // protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    // Optional: set a group in the sidebar
    // protected static string $navigationGroup = 'Reports';

    // This makes it show in the navigation
    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.pages.sales-report';
}

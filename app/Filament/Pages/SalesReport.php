<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Facades\Filament;

class SalesReport extends Page
{
    protected static ?string $title = 'Sales Report';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';
    protected ?string $heading = 'Sales Report';
    protected static ?string $navigationLabel = 'Sales Report';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.pages.sales-report';

    public static function canAccess(): bool
    {
        return Filament::auth()->user()->can('view:_sales_report');
    }

}

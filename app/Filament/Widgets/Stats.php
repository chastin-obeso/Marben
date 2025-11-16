<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\JobOrder;
use App\Models\ServiceInvoice;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Stats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = now()->startOfMonth();
        $endDate = now();

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Revenue
        $revenueQuery = ServiceInvoice::query()
            ->whereBetween('payment_date', [$startDate, $endDate]);

        $revenue = $revenueQuery->sum('amount_paid');

        $revenueTrend = $revenueQuery
            ->orderBy('payment_date')
            ->take(100)
            ->pluck('amount_paid')
            ->toArray();

        // Job Orders
        $newOrdersQuery = JobOrder::query()
            ->whereBetween('date_requested', [$startDate, $endDate]);

        $newOrders = $newOrdersQuery->count();

        // Employee of the Month
        $employeeOfMonth = JobOrder::query()
            ->selectRaw('user_id, COUNT(*) as total_orders')
            ->whereBetween('date_requested', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('total_orders')
            ->first();

        $employeeName = $employeeOfMonth
            ? User::find($employeeOfMonth->user_id)->name
            : 'N/A';

        $employeeOrders = $employeeOfMonth
            ? $employeeOfMonth->total_orders
            : 0;

        $formatNumber = fn(int $number): string => match (true) {
            $number < 1000 => (string) $number,
            $number < 1000000 => round($number / 1000, 2) . 'k',
            default => round($number / 1000000, 2) . 'm',
        };

        return [
            Stat::make('Revenue', '₱' . $formatNumber($revenue))
                ->description('Revenue ' )
                ->descriptionIcon(
                    !empty($revenueTrend) && count($revenueTrend) > 1
                        ? ($revenueTrend[count($revenueTrend) - 1] > $revenueTrend[0]
                            ? 'heroicon-m-arrow-trending-up'
                            : ($revenueTrend[count($revenueTrend) - 1] < $revenueTrend[0]
                                ? 'heroicon-m-arrow-trending-down'
                                : 'heroicon-m-minus'))
                        : 'heroicon-m-minus'
                ) 
                ->chart($revenueTrend)
                ->color('primary'),

            Stat::make('Job Orders', $formatNumber($newOrders))
                ->description('Job Orders this month')
                ->color('primary'),
            Stat::make('Employee of the Month', $employeeName)
                ->description("Most orders: {$employeeOrders}")
                ->color('primary'),
        ];
    }
}

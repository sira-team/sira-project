<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Camp\Models\Camp;

final class CampCostCalculatorWidget extends StatsOverviewWidget
{
    public ?Camp $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $camp = $this->record;
        $nights = $camp->nights;
        $totalExpenses = (float) $camp->expenses()->sum('amount');
        $accommodationCost = 0;
        $confirmedCount = $camp->visitors->count();
        $usersCount = $camp->users->count();

        $participants = max($camp->contract->contracted_beds, ($confirmedCount + $usersCount));
        $accommodationCost = $camp->contract->price_per_person_per_night * $participants * $nights;

        $grandTotal = $accommodationCost + $totalExpenses;

        $predictedPrice = $grandTotal / max(1, $confirmedCount);

        $confirmedPrice = $grandTotal / max(1, $confirmedCount);

        $netPerParticipant = $camp->price_per_participant - $predictedPrice;

        $covered = $camp->price_per_participant * $confirmedCount;

        return [
            Stat::make('Accommodation Cost', '€'.number_format($accommodationCost, 2))
                ->description($nights.' nights'),

            Stat::make('Other Expenses', '€'.number_format($totalExpenses, 2))
                ->description('All categories'),

            Stat::make('Grand Total', '€'.number_format($grandTotal, 2))
                ->description('Accommodation + Expenses')
                ->color($grandTotal < 0 ? 'danger' : 'success'),

            Stat::make('Net profit', '€'.number_format($covered - $grandTotal, 2))
                ->description('Based on '.$confirmedCount.' confirmed'),

            Stat::make('Covered Participants', '€'.number_format($covered, 2))
                ->description('Based on '.$confirmedCount.' confirmed'),
        ];
    }
}

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
        $totalExpenses = (float) $camp->campExpenses()->sum('amount');
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
            Stat::make(__('Accommodation Cost'), '€'.number_format($accommodationCost, 2))
                ->description($nights.' nights'),

            Stat::make(__('Other Expenses'), '€'.number_format($totalExpenses, 2))
                ->description(__('All categories')),

            Stat::make(__('Grand Total'), '€'.number_format($grandTotal, 2))
                ->description(__('Accommodation + Expenses'))
                ->color($grandTotal < 0 ? 'danger' : 'success'),

            Stat::make(__('Net profit'), '€'.number_format($covered - $grandTotal, 2))
                ->description(__('Based on ').$confirmedCount.' confirmed'),

            Stat::make(__('Covered Participants'), '€'.number_format($covered, 2))
                ->description(__('Based on ').$confirmedCount.' confirmed'),
        ];
    }
}

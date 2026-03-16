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
        $totalExpenses = $camp->expenses()->sum('amount');
        $accommodationCost = 0;

        if ($camp->hostelContract) {
            $totalParticipants = ($camp->predicted_participants ?? 0) + ($camp->predicted_supporters ?? 0);
            $accommodationCost = $camp->hostelContract->price_per_person_per_night * $totalParticipants * $nights;
        }

        $grandTotal = $accommodationCost + $totalExpenses;
        $confirmedCount = $camp->confirmedRegistrationsCount;

        $predictedPrice = ($camp->predicted_participants ?? 0) > 0
            ? $grandTotal / $camp->predicted_participants
            : 0;

        $confirmedPrice = $confirmedCount > 0
            ? $grandTotal / $confirmedCount
            : 0;

        return [
            Stat::make('Accommodation Cost', '€'.number_format($accommodationCost, 2))
                ->description($nights.' nights'),

            Stat::make('Total Expenses', '€'.number_format($totalExpenses, 2))
                ->description('All categories'),

            Stat::make('Grand Total', '€'.number_format($grandTotal, 2))
                ->description('Accommodation + Expenses')
                ->color($grandTotal < 0 ? 'danger' : 'success'),

            Stat::make('Predicted Price/Participant', '€'.number_format($predictedPrice, 2))
                ->description('Based on '.($camp->predicted_participants ?? 0).' participants'),

            Stat::make('Confirmed Price/Participant', '€'.number_format($confirmedPrice, 2))
                ->description('Based on '.$confirmedCount.' confirmed'),
        ];
    }
}

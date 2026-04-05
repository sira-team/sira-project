<?php

declare(strict_types=1);

namespace Modules\Camp\Console\Commands;

use Illuminate\Console\Command;
use Modules\Camp\Models\Camp;
use Modules\Camp\Services\WaitlistService;

/**
 * Runs daily. For each active, over-subscribed camp, moves confirmed
 * visitors who have not paid within 8 days back to the waitlist end
 * and sends them a waitlisted notification email.
 *
 * "Active" means the camp has not yet ended (ends_at >= today).
 * "Over-subscribed" means confirmed + pending + paid count > contracted_beds.
 */
final class ExpireUnpaidRegistrationsCommand extends Command
{
    protected $signature = 'camp:expire-unpaid-registrations';

    protected $description = 'Move confirmed, unpaid registrations older than 8 days to the waitlist end for over-subscribed camps';

    public function __construct(private readonly WaitlistService $waitlistService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $camps = Camp::query()
            ->with(['contract', 'tenant'])
            ->whereDate('starts_at', '>=', today()->addDays(7))
            ->whereHas('contract')
            ->get();

        foreach ($camps as $camp) {
            $bar = $this->output->createProgressBar(count($camps));
            $bar->start();
            $this->processCamp($camp);
            $bar->advance();
        }

        return self::SUCCESS;
    }

    private function processCamp(Camp $camp): void
    {
        $expired = $this->waitlistService->expire($camp);

        if ($expired > 0) {
            $this->waitlistService->promote($camp);
        }
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Mails\CampTemplateMail;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampEmailTemplate;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Services\MergeTagReplacer;

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

    public function __construct(private readonly MergeTagReplacer $replacer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $camps = Camp::query()
            ->with(['contract', 'tenant'])
            ->whereDate('ends_at', '>=', now())
            ->get();

        foreach ($camps as $camp) {
            $this->processCamp($camp);
        }

        return self::SUCCESS;
    }

    private function processCamp(Camp $camp): void
    {
        if ($camp->contract === null) {
            return;
        }

        $activeCount = CampVisitor::query()
            ->where('camp_id', $camp->id)
            ->whereIn('status', [
                VisitorStatus::Pending->value,
                VisitorStatus::Confirmed->value,
                VisitorStatus::Paid->value,
            ])
            ->count();

        if ($activeCount <= $camp->contract->contracted_beds) {
            return;
        }

        $expired = CampVisitor::query()
            ->with(['visitor.guardians'])
            ->where('camp_id', $camp->id)
            ->where('status', VisitorStatus::Confirmed->value)
            ->where('registered_at', '<', now()->subDays(8))
            ->get();

        foreach ($expired as $campVisitor) {
            $this->expireRegistration($camp, $campVisitor);
        }
    }

    private function expireRegistration(Camp $camp, CampVisitor $campVisitor): void
    {
        $maxPosition = CampVisitor::query()
            ->where('camp_id', $camp->id)
            ->where('status', VisitorStatus::Waitlisted->value)
            ->max('waitlist_position') ?? 0;

        $campVisitor->update([
            'status' => VisitorStatus::Waitlisted,
            'waitlist_position' => $maxPosition + 1,
        ]);

        $this->sendWaitlistedEmail($camp, $campVisitor);
    }

    private function sendWaitlistedEmail(Camp $camp, CampVisitor $campVisitor): void
    {
        $template = CampEmailTemplate::withoutGlobalScopes()
            ->where('tenant_id', $camp->tenant_id)
            ->where('key', CampNotificationType::Waitlisted->value)
            ->first();

        if ($template === null) {
            return;
        }

        $recipientEmails = $this->resolveRecipientEmail($campVisitor);

        ['subject' => $subject, 'body' => $body] = $this->replacer->resolve($template, [
            'visitor_name' => $campVisitor->visitor->name,
            'camp_name' => $camp->name,
            'tenant_name' => $camp->tenant->name,
            'waitlist_position' => (string) $campVisitor->waitlist_position,
        ]);

        Mail::to($recipientEmails)->queue(new CampTemplateMail($subject, $body));
    }

    private function resolveRecipientEmail(CampVisitor $campVisitor): array
    {
        $visitor = $campVisitor->visitor;

        $emails = $visitor->guardians()->pluck('email')->filter()->all();

        if ($visitor->email) {
            $emails[] = $visitor->email;
        }

        return $emails;
    }
}

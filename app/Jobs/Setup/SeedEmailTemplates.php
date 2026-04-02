<?php

declare(strict_types=1);

namespace App\Jobs\Setup;

use App\Models\EmailTemplate;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Seeds default email templates for a tenant idempotently.
 * Called by TenantObserver on creation.
 */
final class SeedEmailTemplates
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Tenant $tenant) {}

    public function handle(): void
    {
        foreach (EmailTemplate::defaults() as $key => $content) {
            EmailTemplate::withoutGlobalScopes()->firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'key' => $key],
                ['subject' => $content['subject'], 'body' => $content['body'], 'scope' => $content['scope']]
            );
        }
    }
}

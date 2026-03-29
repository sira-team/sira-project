<?php

declare(strict_types=1);

namespace Modules\Camp\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Modules\Camp\Database\Factories\CampEmailTemplateFactory;
use Modules\Camp\Models\CampEmailTemplate;

final class SeedCampEmailTemplatesCommand extends Command
{
    protected $signature = 'camp:seed-email-templates
                            {--tenant= : Tenant ID (seeds all tenants if omitted)}
                            {--force : Overwrite existing templates with default content}';

    protected $description = 'Seed default camp email templates for tenants that are missing them';

    public function handle(): int
    {
        $tenants = filled($this->option('tenant'))
            ? Tenant::where('id', $this->option('tenant'))->get()
            : Tenant::all();

        $force = (bool) $this->option('force');

        foreach ($tenants as $tenant) {
            foreach (CampEmailTemplateFactory::defaults() as $key => $content) {
                if ($force) {
                    CampEmailTemplate::withoutGlobalScopes()->updateOrCreate(
                        ['tenant_id' => $tenant->id, 'key' => $key],
                        ['subject' => $content['subject'], 'body' => $content['body']]
                    );
                } else {
                    CampEmailTemplate::withoutGlobalScopes()->firstOrCreate(
                        ['tenant_id' => $tenant->id, 'key' => $key],
                        ['subject' => $content['subject'], 'body' => $content['body']]
                    );
                }
            }

            $this->info("Seeded email templates for tenant: {$tenant->name}");
        }

        return self::SUCCESS;
    }
}

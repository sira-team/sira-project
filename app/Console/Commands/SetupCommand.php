<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\Setup\CreateAdminUser;
use App\Jobs\Setup\CreateDefaultTenant;
use App\Jobs\Setup\GrantFeatureAccess;
use Illuminate\Console\Command;

final class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the initial tenant, admin user, and feature flags';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch_sync(new CreateDefaultTenant);
        $this->info('Created Default Tenant');

        dispatch_sync(new CreateAdminUser);
        $this->info('Created Admin Users');

        dispatch_sync(new GrantFeatureAccess);
        $this->info('Granted Feature Access');
    }
}

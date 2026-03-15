<?php

declare(strict_types=1);

namespace App\Jobs\Setup;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class CreateDefaultTenant
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function beforeCommit() {}

    public function afterCommit() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var array<string, mixed> $tenant */
        $tenant = config('setup.tenant');

        Tenant::firstOrCreate([
            'slug' => $tenant['slug'],
        ], [
            'name' => $tenant['name'],
            'city' => $tenant['city'],
            'country' => $tenant['country'],
            'email' => $tenant['email'],
        ]);
    }
}

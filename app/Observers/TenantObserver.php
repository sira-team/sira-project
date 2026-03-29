<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Setup\AssignRolePermissions;
use App\Models\Tenant;
use Modules\Camp\Database\Factories\CampEmailTemplateFactory;
use Modules\Camp\Models\CampEmailTemplate;
use Spatie\Permission\Models\Role;

final class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        $teamKey = config('permission.column_names.tenant_foreign_key');

        setPermissionsTeamId($tenant->id);

        foreach (array_keys(AssignRolePermissions::roleResourceMap()) as $roleName) {
            Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                $teamKey => $tenant->id,
            ]);
        }

        dispatch_sync(new AssignRolePermissions($tenant));

        foreach (CampEmailTemplateFactory::defaults() as $key => $content) {
            CampEmailTemplate::withoutGlobalScopes()->firstOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['subject' => $content['subject'], 'body' => $content['body']]
            );
        }
    }
}

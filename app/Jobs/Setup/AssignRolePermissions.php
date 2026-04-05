<?php

declare(strict_types=1);

namespace App\Jobs\Setup;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Assigns Shield-generated permissions to tenant roles idempotently.
 *
 * To update what a role can access, edit the roleResourceMap below.
 * Resource names are the model base names used by Shield, i.e., the part
 * after ':' in permission names like 'ViewAny:Expo'.
 *
 * Called by TenantObserver on creation and can be dispatched any time
 * permissions change (e.g., after adding a new resource and re-running
 * shield:generate).
 */
final class AssignRolePermissions
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Tenant $tenant) {}

    /**
     * Maps each tenant role to the Shield resource names it should control.
     *
     * '*' — grant all permissions in the database (e.g., tenant_admin)
     * [] — grant no permissions (access controlled by policy logic only)
     * [...] — grant all Shield permissions whose name ends in ':{ResourceName}'
     *
     * Add new resource base names here when a module gains new resources.
     *
     * @return array<string, list<string>|string>
     */
    public static function roleResourceMap(): array
    {
        return [
            'tenant_admin' => '*',

            // Expo module — Modules/Expo
            'expo_manager' => ['Expo', 'ExpoRequest', 'Station'],

            // Camp module — Modules/Camp
            'camp_manager' => ['Camp', 'Hostel', 'EmailTemplate'],

            // Academy module — Modules/Academy
            'academy_manager' => ['Enrollment'],

            // Members only access their own academy dashboard via policy
            'member' => [],
        ];
    }

    public function handle(PermissionRegistrar $registrar): void
    {
        $teamKey = config('permission.column_names.tenant_foreign_key');

        setPermissionsTeamId($this->tenant->id);

        $roles = Role::query()
            ->where('guard_name', 'web')
            ->where($teamKey, $this->tenant->id)
            ->get()
            ->keyBy('name');

        foreach (self::roleResourceMap() as $roleName => $resources) {
            $role = $roles->get($roleName);

            if (! $role instanceof Role) {
                continue;
            }

            $permissions = match (true) {
                $resources === '*' => Permission::where('guard_name', 'web')->pluck('id'),
                empty($resources) => collect(),
                default => Permission::where('guard_name', 'web')
                    ->where(function (Builder $query) use ($resources): void {
                        foreach ($resources as $resource) {
                            $query->orWhere('name', 'like', "%:{$resource}");
                        }
                    })
                    ->pluck('id'),
            };

            $role->syncPermissions($permissions);
        }

        $registrar->forgetCachedPermissions();
    }
}

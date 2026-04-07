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

final class CreateRolesForTenant
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Tenant $tenant) {}

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
            'expo_manager' => ['Expo', 'ExpoRequest', 'Station', 'EmailTemplate'],

            // Camp module — Modules/Camp
            'camp_manager' => ['Camp', 'Hostel', 'EmailTemplate'],

            // Academy module — Modules/Academy
            'academy_manager' => ['Enrollment'],

            // Members only access their own academy dashboard via policy
            'member' => [],
        ];
    }

    public function handle(): void
    {
        $teamKey = config('permission.column_names.tenant_foreign_key');
        setPermissionsTeamId($this->tenant->id);

        foreach (self::roleResourceMap() as $roleName => $resources) {
            $role = Role::query()->create([
                'name' => trans('roles.'.$roleName, locale: app()->getLocale()),
                'guard_name' => 'web',
                $teamKey => $this->tenant->id,
            ]);

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
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Team;
use Spatie\Permission\Models\Role;

class TeamObserver
{
    public function created(Team $team): void
    {
        setPermissionsTeamId($team->id);

        $roles = [
            'tenant_admin',
            'academy_manager',
            'camp_manager',
            'expo_manager',
            'member',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'web',
                'team_id' => $team->id,
            ]);
        }
    }
}

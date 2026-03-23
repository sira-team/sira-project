<?php

declare(strict_types=1);

namespace App\Support\Permissions;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\DefaultTeamResolver;

final class TeamResolver extends DefaultTeamResolver
{
    public function getPermissionsTeamId(): int|string|null
    {
        if ($this->teamId !== null) {
            return $this->teamId;
        }

        $panel = Filament::getCurrentPanel();
        if ($panel instanceof Panel) {
            if ($panel->hasTenancy()) {
                return Filament::getTenant()->getKey();
            }

            return null;
        }

        if (Auth::check()) {
            return Auth::user()->tenant_id;
        }

        return null;
    }
}

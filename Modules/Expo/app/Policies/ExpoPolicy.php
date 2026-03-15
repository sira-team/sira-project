<?php

declare(strict_types=1);

namespace Modules\Expo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Expo\Models\Expo;

class ExpoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Expo');
    }

    public function view(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('View:Expo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Expo');
    }

    public function update(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('Update:Expo');
    }

    public function delete(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('Delete:Expo');
    }

    public function restore(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('Restore:Expo');
    }

    public function forceDelete(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('ForceDelete:Expo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Expo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Expo');
    }

    public function replicate(AuthUser $authUser, Expo $expo): bool
    {
        return $authUser->can('Replicate:Expo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Expo');
    }
}

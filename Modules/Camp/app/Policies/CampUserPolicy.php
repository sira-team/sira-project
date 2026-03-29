<?php

declare(strict_types=1);

namespace Modules\Camp\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Camp\Models\CampUser;

final class CampUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CampUser');
    }

    public function view(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('View:CampUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CampUser');
    }

    public function update(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('Update:CampUser');
    }

    public function delete(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('Delete:CampUser');
    }

    public function restore(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('Restore:CampUser');
    }

    public function forceDelete(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('ForceDelete:CampUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CampUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CampUser');
    }

    public function replicate(AuthUser $authUser, CampUser $campUser): bool
    {
        return $authUser->can('Replicate:CampUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CampUser');
    }
}

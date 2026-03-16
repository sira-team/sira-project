<?php

declare(strict_types=1);

namespace Modules\Camp\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Camp\Models\Camp;

final class CampPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser): bool
    {
        return true;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Camp');
    }

    public function view(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('View:Camp');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Camp');
    }

    public function update(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('Update:Camp');
    }

    public function delete(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('Delete:Camp');
    }

    public function restore(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('Restore:Camp');
    }

    public function forceDelete(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('ForceDelete:Camp');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Camp');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Camp');
    }

    public function replicate(AuthUser $authUser, Camp $camp): bool
    {
        return $authUser->can('Replicate:Camp');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Camp');
    }
}

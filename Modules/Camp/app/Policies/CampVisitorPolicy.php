<?php

declare(strict_types=1);

namespace Modules\Camp\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Camp\Models\CampVisitor;

final class CampVisitorPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CampVisitor');
    }

    public function view(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('View:CampVisitor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CampVisitor');
    }

    public function update(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('Update:CampVisitor');
    }

    public function delete(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('Delete:CampVisitor');
    }

    public function restore(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('Restore:CampVisitor');
    }

    public function forceDelete(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('ForceDelete:CampVisitor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CampVisitor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CampVisitor');
    }

    public function replicate(AuthUser $authUser, CampVisitor $campVisitor): bool
    {
        return $authUser->can('Replicate:CampVisitor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CampVisitor');
    }
}

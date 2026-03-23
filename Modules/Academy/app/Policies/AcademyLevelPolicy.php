<?php

declare(strict_types=1);

namespace Modules\Academy\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Academy\Models\AcademyLevel;
use Modules\Academy\Traits\CheckAcademyContentManager;

final class AcademyLevelPolicy
{
    use CheckAcademyContentManager, HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AcademyLevel');
    }

    public function view(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('View:AcademyLevel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AcademyLevel');
    }

    public function update(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('Update:AcademyLevel');
    }

    public function delete(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('Delete:AcademyLevel');
    }

    public function restore(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('Restore:AcademyLevel');
    }

    public function forceDelete(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('ForceDelete:AcademyLevel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AcademyLevel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AcademyLevel');
    }

    public function replicate(AuthUser $authUser, AcademyLevel $academyLevel): bool
    {
        return $authUser->can('Replicate:AcademyLevel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AcademyLevel');
    }
}

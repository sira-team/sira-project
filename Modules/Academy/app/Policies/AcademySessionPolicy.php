<?php

declare(strict_types=1);

namespace Modules\Academy\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Traits\CheckAcademyContentManager;

final class AcademySessionPolicy
{
    use CheckAcademyContentManager, HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AcademySession');
    }

    public function view(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('View:AcademySession');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AcademySession');
    }

    public function update(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('Update:AcademySession');
    }

    public function delete(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('Delete:AcademySession');
    }

    public function restore(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('Restore:AcademySession');
    }

    public function forceDelete(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('ForceDelete:AcademySession');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AcademySession');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AcademySession');
    }

    public function replicate(AuthUser $authUser, AcademySession $academySession): bool
    {
        return $authUser->can('Replicate:AcademySession');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AcademySession');
    }
}

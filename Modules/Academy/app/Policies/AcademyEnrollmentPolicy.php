<?php

declare(strict_types=1);

namespace Modules\Academy\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Academy\Models\AcademyEnrollment;

final class AcademyEnrollmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AcademyEnrollment');
    }

    public function view(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('View:AcademyEnrollment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AcademyEnrollment');
    }

    public function update(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('Update:AcademyEnrollment');
    }

    public function delete(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('Delete:AcademyEnrollment');
    }

    public function restore(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('Restore:AcademyEnrollment');
    }

    public function forceDelete(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('ForceDelete:AcademyEnrollment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AcademyEnrollment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AcademyEnrollment');
    }

    public function replicate(AuthUser $authUser, AcademyEnrollment $academyEnrollment): bool
    {
        return $authUser->can('Replicate:AcademyEnrollment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AcademyEnrollment');
    }
}

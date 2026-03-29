<?php

declare(strict_types=1);

namespace Modules\Camp\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Camp\Models\CampEmailTemplate;

final class CampEmailTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CampEmailTemplate');
    }

    public function view(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('View:CampEmailTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CampEmailTemplate');
    }

    public function update(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('Update:CampEmailTemplate');
    }

    public function delete(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('Delete:CampEmailTemplate');
    }

    public function restore(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('Restore:CampEmailTemplate');
    }

    public function forceDelete(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('ForceDelete:CampEmailTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CampEmailTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CampEmailTemplate');
    }

    public function replicate(AuthUser $authUser, CampEmailTemplate $campEmailTemplate): bool
    {
        return $authUser->can('Replicate:CampEmailTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CampEmailTemplate');
    }
}

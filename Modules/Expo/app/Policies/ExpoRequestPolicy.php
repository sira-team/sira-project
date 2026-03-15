<?php

declare(strict_types=1);

namespace Modules\Expo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Expo\Models\ExpoRequest;

class ExpoRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExpoRequest');
    }

    public function view(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('View:ExpoRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExpoRequest');
    }

    public function update(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('Update:ExpoRequest');
    }

    public function delete(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('Delete:ExpoRequest');
    }

    public function restore(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('Restore:ExpoRequest');
    }

    public function forceDelete(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('ForceDelete:ExpoRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExpoRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExpoRequest');
    }

    public function replicate(AuthUser $authUser, ExpoRequest $expoRequest): bool
    {
        return $authUser->can('Replicate:ExpoRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExpoRequest');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\FeatureFlag;
use App\Models\Tenant;
use Feature;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

final class TenantPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser, string $ability): ?bool
    {
        if (Feature::for($authUser)->active(FeatureFlag::GlobalAdmin->value)) {
            return true;
        }

        return null;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tenant');
    }

    public function view(AuthUser $authUser, Tenant $tenant): bool
    {
        return $authUser->can('View:Tenant');
    }

    public function create(AuthUser $authUser): bool
    {
        return false;
    }

    public function update(AuthUser $authUser, Tenant $tenant): bool
    {
        return $authUser->can('Update:Tenant');
    }

    public function delete(AuthUser $authUser, Tenant $tenant): bool
    {
        return false;
    }

    public function restore(AuthUser $authUser, Tenant $tenant): bool
    {
        return false;
    }

    public function forceDelete(AuthUser $authUser, Tenant $tenant): bool
    {
        return false;
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return false;
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return false;
    }

    public function replicate(AuthUser $authUser, Tenant $tenant): bool
    {
        return false;
    }

    public function reorder(AuthUser $authUser): bool
    {
        return false;
    }
}

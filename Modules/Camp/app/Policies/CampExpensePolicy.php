<?php

declare(strict_types=1);

namespace Modules\Camp\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Camp\Models\CampExpense;

final class CampExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CampExpense');
    }

    public function view(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('View:CampExpense');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CampExpense');
    }

    public function update(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('Update:CampExpense');
    }

    public function delete(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('Delete:CampExpense');
    }

    public function restore(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('Restore:CampExpense');
    }

    public function forceDelete(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('ForceDelete:CampExpense');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CampExpense');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CampExpense');
    }

    public function replicate(AuthUser $authUser, CampExpense $campExpense): bool
    {
        return $authUser->can('Replicate:CampExpense');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CampExpense');
    }
}

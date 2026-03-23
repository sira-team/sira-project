<?php

declare(strict_types=1);

namespace Modules\Academy\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Academy\Models\QuizOption;
use Modules\Academy\Traits\CheckAcademyContentManager;

final class QuizOptionPolicy
{
    use CheckAcademyContentManager, HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuizOption');
    }

    public function view(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('View:QuizOption');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuizOption');
    }

    public function update(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('Update:QuizOption');
    }

    public function delete(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('Delete:QuizOption');
    }

    public function restore(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('Restore:QuizOption');
    }

    public function forceDelete(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('ForceDelete:QuizOption');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuizOption');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuizOption');
    }

    public function replicate(AuthUser $authUser, QuizOption $quizOption): bool
    {
        return $authUser->can('Replicate:QuizOption');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuizOption');
    }
}

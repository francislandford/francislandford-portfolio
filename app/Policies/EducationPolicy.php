<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Education;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class EducationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Education');
    }

    public function view(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('View:Education');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Education');
    }

    public function update(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('Update:Education');
    }

    public function delete(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('Delete:Education');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Education');
    }

    public function restore(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('Restore:Education');
    }

    public function forceDelete(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('ForceDelete:Education');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Education');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Education');
    }

    public function replicate(AuthUser $authUser, Education $education): bool
    {
        return $authUser->can('Replicate:Education');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Education');
    }
}

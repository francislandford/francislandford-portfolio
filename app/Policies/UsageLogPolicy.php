<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\UsageLog;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UsageLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UsageLog');
    }

    public function view(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('View:UsageLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UsageLog');
    }

    public function update(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('Update:UsageLog');
    }

    public function delete(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('Delete:UsageLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:UsageLog');
    }

    public function restore(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('Restore:UsageLog');
    }

    public function forceDelete(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('ForceDelete:UsageLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UsageLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UsageLog');
    }

    public function replicate(AuthUser $authUser, UsageLog $usageLog): bool
    {
        return $authUser->can('Replicate:UsageLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UsageLog');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\GithubDigest;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class GithubDigestPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GithubDigest');
    }

    public function view(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('View:GithubDigest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GithubDigest');
    }

    public function update(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('Update:GithubDigest');
    }

    public function delete(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('Delete:GithubDigest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:GithubDigest');
    }

    public function restore(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('Restore:GithubDigest');
    }

    public function forceDelete(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('ForceDelete:GithubDigest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GithubDigest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GithubDigest');
    }

    public function replicate(AuthUser $authUser, GithubDigest $githubDigest): bool
    {
        return $authUser->can('Replicate:GithubDigest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GithubDigest');
    }
}

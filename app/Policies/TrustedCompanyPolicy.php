<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TrustedCompany;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TrustedCompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TrustedCompany');
    }

    public function view(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('View:TrustedCompany');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TrustedCompany');
    }

    public function update(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('Update:TrustedCompany');
    }

    public function delete(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('Delete:TrustedCompany');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TrustedCompany');
    }

    public function restore(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('Restore:TrustedCompany');
    }

    public function forceDelete(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('ForceDelete:TrustedCompany');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TrustedCompany');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TrustedCompany');
    }

    public function replicate(AuthUser $authUser, TrustedCompany $trustedCompany): bool
    {
        return $authUser->can('Replicate:TrustedCompany');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TrustedCompany');
    }
}

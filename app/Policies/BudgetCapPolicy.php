<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BudgetCap;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BudgetCapPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BudgetCap');
    }

    public function view(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('View:BudgetCap');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BudgetCap');
    }

    public function update(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('Update:BudgetCap');
    }

    public function delete(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('Delete:BudgetCap');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BudgetCap');
    }

    public function restore(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('Restore:BudgetCap');
    }

    public function forceDelete(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('ForceDelete:BudgetCap');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BudgetCap');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BudgetCap');
    }

    public function replicate(AuthUser $authUser, BudgetCap $budgetCap): bool
    {
        return $authUser->can('Replicate:BudgetCap');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BudgetCap');
    }
}

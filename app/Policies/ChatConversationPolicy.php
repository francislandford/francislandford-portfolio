<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ChatConversation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ChatConversationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChatConversation');
    }

    public function view(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('View:ChatConversation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChatConversation');
    }

    public function update(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('Update:ChatConversation');
    }

    public function delete(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('Delete:ChatConversation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ChatConversation');
    }

    public function restore(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('Restore:ChatConversation');
    }

    public function forceDelete(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('ForceDelete:ChatConversation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChatConversation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChatConversation');
    }

    public function replicate(AuthUser $authUser, ChatConversation $chatConversation): bool
    {
        return $authUser->can('Replicate:ChatConversation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChatConversation');
    }
}

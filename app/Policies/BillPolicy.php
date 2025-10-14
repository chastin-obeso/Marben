<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Bill');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:Bill');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Bill');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:Bill');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:Bill');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:Bill');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:Bill');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Bill');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Bill');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:Bill');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Bill');
    }

}
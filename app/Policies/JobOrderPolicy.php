<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JobOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobOrderPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JobOrder');
    }

    public function view(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('View:JobOrder');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JobOrder');
    }

    public function update(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Update:JobOrder');
    }

    public function delete(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Delete:JobOrder');
    }

    public function restore(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Restore:JobOrder');
    }

    public function forceDelete(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('ForceDelete:JobOrder');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JobOrder');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JobOrder');
    }

    public function replicate(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Replicate:JobOrder');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JobOrder');
    }

}
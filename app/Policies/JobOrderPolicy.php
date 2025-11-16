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
        return $authUser->can('ViewAny:JobOrder') || $authUser->can('ViewAssigned:JobOrder');
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

    public function viewAssigned(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('ViewAssigned:JobOrder');
    }

    public function start(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Start:JobOrder');
    }

    public function hold(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Hold:JobOrder');
    }

    public function complete(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Complete:JobOrder');
    }

    public function resume(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Resume:JobOrder');
    }

    public function close(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Close:JobOrder');
    }

    public function cancel(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Cancel:JobOrder');
    }

    public function rejob(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('Rejob:JobOrder');
    }

    public function createLog(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('CreateLog:JobOrder');
    }

    public function editLog(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('EditLog:JobOrder');
    }

    public function createJobOrderPart(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('CreateJobOrderPart:JobOrder');
    }

    public function editJobOrderPart(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('EditJobOrderPart:JobOrder');
    }

    public function deleteJobOrderPart(AuthUser $authUser, JobOrder $jobOrder): bool
    {
        return $authUser->can('DeleteJobOrderPart:JobOrder');
    }

}
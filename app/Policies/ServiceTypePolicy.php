<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ServiceType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServiceType');
    }

    public function view(AuthUser $authUser, ServiceType $serviceType): bool
    {
        return $authUser->can('View:ServiceType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServiceType');
    }

    public function update(AuthUser $authUser, ServiceType $serviceType): bool
    {
        return $authUser->can('Update:ServiceType');
    }

}
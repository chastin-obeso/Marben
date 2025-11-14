<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Bill;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Bill');
    }

    public function view(AuthUser $authUser, Bill $bill): bool
    {
        return $authUser->can('View:Bill');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Bill');
    }

    public function update(AuthUser $authUser, Bill $bill): bool
    {
        return $authUser->can('Update:Bill');
    }

}
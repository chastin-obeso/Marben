<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ServiceInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceInvoicePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ServiceInvoice');
    }

    public function view(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('View:ServiceInvoice');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ServiceInvoice');
    }

    public function update(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('Update:ServiceInvoice');
    }

    public function delete(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('Delete:ServiceInvoice');
    }

}
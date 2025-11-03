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

    public function restore(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('Restore:ServiceInvoice');
    }

    public function forceDelete(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('ForceDelete:ServiceInvoice');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ServiceInvoice');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ServiceInvoice');
    }

    public function replicate(AuthUser $authUser, ServiceInvoice $serviceInvoice): bool
    {
        return $authUser->can('Replicate:ServiceInvoice');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ServiceInvoice');
    }

}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Customer","View:Customer","Create:Customer","Update:Customer","Delete:Customer","Restore:Customer","ForceDelete:Customer","ForceDeleteAny:Customer","RestoreAny:Customer","Replicate:Customer","Reorder:Customer","ViewAny:JobOrder","View:JobOrder","Create:JobOrder","Update:JobOrder","Delete:JobOrder","Restore:JobOrder","ForceDelete:JobOrder","ForceDeleteAny:JobOrder","RestoreAny:JobOrder","Replicate:JobOrder","Reorder:JobOrder","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:Bill","View:Bill","Create:Bill","Update:Bill","Delete:Bill","Restore:Bill","ForceDelete:Bill","ForceDeleteAny:Bill","RestoreAny:Bill","Replicate:Bill","Reorder:Bill","ViewAny:ServiceInvoice","View:ServiceInvoice","Create:ServiceInvoice","Update:ServiceInvoice","Delete:ServiceInvoice","Restore:ServiceInvoice","ForceDelete:ServiceInvoice","ForceDeleteAny:ServiceInvoice","RestoreAny:ServiceInvoice","Replicate:ServiceInvoice","Reorder:ServiceInvoice","ViewAny:ServiceType","View:ServiceType","Create:ServiceType","Update:ServiceType","Delete:ServiceType","Restore:ServiceType","ForceDelete:ServiceType","ForceDeleteAny:ServiceType","RestoreAny:ServiceType","Replicate:ServiceType","Reorder:ServiceType","View:Dashboard","View:Calendar","View:JobOrderTableWidget"]},{"name":"view_only","guard_name":"web","permissions":["ViewAny:Customer","View:Customer","ViewAny:JobOrder","View:JobOrder","ViewAny:Role","View:Role","ViewAny:User","View:User"]},{"name":"Service Personnel","guard_name":"web","permissions":["ViewAny:Customer","View:Customer","Create:Customer","Update:Customer","Restore:Customer","ForceDelete:Customer","ForceDeleteAny:Customer","RestoreAny:Customer","Replicate:Customer","Reorder:Customer","ViewAny:JobOrder","View:JobOrder","Create:JobOrder","Update:JobOrder","Restore:JobOrder","ForceDelete:JobOrder","ForceDeleteAny:JobOrder","RestoreAny:JobOrder","Replicate:JobOrder","Reorder:JobOrder","ViewAny:User","View:User"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}

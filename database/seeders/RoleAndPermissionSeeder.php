<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        // create permissions
        $permissions = [
            'home-access',

            'manage-menu-access',
            'manage-menu-create',
            'manage-menu-edit',
            'manage-menu-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        //Role::create(['name' => 'Super-Admin']);
        $role = Role::create(['name' => 'Su-Admin']);

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        $adminPermissions = [
            ...$permissions
        ];
        $adminRole = Role::create(['name' => 'Admin']);
        foreach ($adminPermissions as $permission) {
            $adminRole->givePermissionTo($permission);
        }
        $staffPermissions = [
            ...$permissions
        ];
        $staffRole = Role::create(['name' => 'Staff']);
        foreach ($staffPermissions as $permission) {
            $staffRole->givePermissionTo($permission);
        }
    }
}

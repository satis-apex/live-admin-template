<?php
namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
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
        $permissionList = File::get(base_path('Modules/User/Database/Seeders/PermissionList.json'));
        $permissions = json_decode($permissionList);

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        //Role::create(['name' => 'Super-Admin']);
        $role = Role::create(['name' => 'Su-Admin']);
        $suAdminPermissions = [
            'Impersonate-create',
            'Impersonate-delete',
            ...$permissions
        ];
        foreach ($suAdminPermissions as $permission) {
            $role->givePermissionTo($permission);
        }

        $adminPermissions = [
            'Impersonate-create',
            'Impersonate-delete',
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

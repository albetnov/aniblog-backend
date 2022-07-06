<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'admin dashboard']);
        $role->givePermissionTo($permission);
        $userRole = Role::create(['name' => 'user']);
        $userPermission = Permission::create(['name' => 'user dashboard']);
        $userRole->givePermissionTo($userPermission);
    }
}

<?php

namespace Database\Seeders;

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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $listOfPermissions = [
            'create category',
            'read category',
            'update category',
            'delete category',
            'create blog',
            'read blog',
            'update blog',
            'delete blog',
            'manage users',
            'manage roles',
            'read users'
        ];

        foreach ($listOfPermissions as $listofPermission) {
            Permission::create(['name' => $listofPermission]);
        }

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $powers = [
            'author' => ['read category', 'create blog', 'read blog', 'update blog', 'delete blog', 'read users'],
            'user' => ['read category', 'read blog', 'read users'],
        ];

        $author = Role::create(['name' => 'author']);
        $author->givePermissionTo($powers['author']);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo($powers['user']);
    }
}

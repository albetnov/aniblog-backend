<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'email' => 'admin@mail.com',
            'password' => bcrypt('admin12345'),
            'name' => 'Sang Admin'
        ]);
        $admin->assignRole('admin');
        $user = User::create([
            'email' => 'asep@mail.com',
            'password' => bcrypt('asep12345'),
            'name' => 'Asep Surasep'
        ]);
        $user->assignRole('user');
    }
}

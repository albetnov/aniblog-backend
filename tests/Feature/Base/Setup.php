<?php

namespace Tests\Feature\Base;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\seed;

class Setup
{
    public static function setup()
    {
        seed(RoleSeeder::class);
        seed(UserSeeder::class);
        Sanctum::actingAs(
            User::where('email', 'admin@mail.com')->first()
        );
    }
}

<?php

namespace Tests\Feature\Base;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\seed;
use function Pest\Laravel\withoutMiddleware;

class Setup
{
    public static function setup()
    {
        // Seeding the user
        seed(RoleSeeder::class);
        seed(UserSeeder::class);

        // Acting as superuser to grant all permissions.
        Sanctum::actingAs(
            User::where('email', 'admin@mail.com')->first()
        );

        // Disable throttle middleware.
        withoutMiddleware(ThrottleRequests::class);
    }

    public static function categoryBuilder($makeNotFound = false)
    {
        if ($makeNotFound) {
            $noCategory = Category::orderByDesc('id')->first()->id + 999;
            $random = rand($noCategory, 9999);
            return [$noCategory, $random, $random];
        }

        $categories = Category::inRandomOrder()->limit(3)->pluck('id')->toArray();

        return $categories;
    }

    public static function permissionBuilder($makeNotFound = false)
    {
        if ($makeNotFound) {
            $noPermission = Permission::orderByDesc('id')->first()->id + 999;
            $random = rand($noPermission, 9999);
            return [$noPermission, $random, $random];
        }

        $permissions = Permission::inRandomOrder()->limit(2)->pluck('id')->toArray();

        return $permissions;
    }
}

<?php

namespace Tests\Feature\Base;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\seed;
use function Pest\Laravel\withoutExceptionHandling;
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
            return "{$noCategory},{$random},{$random}";
        }

        $categories = Category::inRandomOrder()->limit(3)->get();

        $lists = "";

        foreach ($categories as $category) {
            $lists .= "{$category->id},";
        }

        return rtrim($lists, ",");
    }
}

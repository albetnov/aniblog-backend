<?php

use App\Models\Blog;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Base\Setup;

use function Pest\Laravel\seed;
use function Pest\Laravel\withHeader;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setup::setup();
    seed(CategorySeeder::class);
});

it('should return list of categories', function () {
    $this->get('/api/categories')->assertOk()->assertJson(
        fn (AssertableJson $json) =>
        $json->has('data')->etc()
    );
});

it('should return single category (success)', function () {
    $category = Category::inRandomOrder()->first()->id;
    $this->get("/api/categories/{$category}")->assertOk()->assertJson(
        fn (AssertableJson $json) =>
        $json->has('name')->has('details')->etc()
    );
});

it('should not return a category. (not found)', function () {
    $noCategory = Category::orderByDesc('id')->first()->id + 999;
    $this->get("/api/categories/{$noCategory}")->assertStatus(404);
});

it('should save a new category', function () {
    $this->postJson('/api/categories', [
        'name' => 'example category',
        'details' => 'this is a example category.'
    ])->assertStatus(201)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('name')->has('details')->etc()
    );
});

it("should failed save a new category (Duplicate)", function () {
    $jsonContent = [
        'name' => 'example category duplicate',
        'details' => 'this is a example category duplicate.'
    ];
    $this->postJson('/api/categories', $jsonContent)->assertStatus(201)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('name')->has('details')->etc()
    );

    $this->postJson('/api/categories', $jsonContent)->assertStatus(422);
});

it("should update the category", function () {
    $category = Category::inRandomOrder()->first()->id;
    $this->putJson("/api/categories/{$category}", [
        'name' => 'example category updated',
        'details' => 'this is a example category updated.'
    ])->assertStatus(200)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('name')->has('details')->etc()
    );
});

it("should failed update category (Not found)", function () {
    $noCategory = Category::orderByDesc('id')->first()->id + 999;
    $this->putJson("/api/categories/{$noCategory}", [
        'name' => 'example category updated',
        'details' => 'this is a example category updated.'
    ])->assertStatus(404);
});

it("should delete a category", function () {
    $category = Category::inRandomOrder()->first()->id;
    $this->delete("/api/categories/{$category}")->assertStatus(200)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('name')->has('details')->etc()
    );
});

it("should not delete a category (not found)", function () {
    $noCategory = Category::orderByDesc('id')->first()->id + 999;
    $this->delete("/api/categories/{$noCategory}")->assertStatus(404);
});

it("should not delete a category (in use)", function () {
    seed(BlogSeeder::class);
    $randomBlog = Blog::with('categories')->inRandomOrder()->first();
    $this->delete("/api/categories/{$randomBlog->categories[0]->id}")->assertStatus(500);
});

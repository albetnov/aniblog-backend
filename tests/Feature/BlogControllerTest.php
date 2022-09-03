<?php

use App\Models\Blog;
use Database\Seeders\BlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Base\Setup;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setup::setup();
    seed(BlogSeeder::class);
});

it("shows list of blog articles", function () {
    $this->get("/api/blogs")->assertOk()->assertJson(
        fn (AssertableJson $json) =>
        $json->has('data',)->etc()
    );
});


it("show single blog (success)", function () {
    $randomBlog = Blog::inRandomOrder()->first()->id;
    $this->get("/api/blogs/{$randomBlog}")->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('title')->has('content')->has('categories')->etc());
});

it("not showing single blog (not found)", function () {
    $noBlog = Blog::orderByDesc('id')->first()->id + 999;
    $this->get("/api/blogs/{$noBlog}")->assertNotFound();
});

it("save a new blog", function () {
    $category = Setup::categoryBuilder();
    $blog = $this->postJson('/api/blogs', [
        'title' => 'This is a new blog',
        'content' => 'This is a new blog dude.',
        'categories' => $category
    ])->assertCreated()->assertJson(fn (AssertableJson $json) => $json->has('title')->has('content')->etc())->json()['id'];

    $this->get("/api/blogs/{$blog}")->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('categories')->etc());
});

it("save a new blog failed (no such category)", function () {
    $category = Setup::categoryBuilder(true);
    $this->postJson("/api/blogs", [
        'title' => 'This is a new blog',
        'content' => 'This is a new blog content',
        'categories' => $category
    ])->assertNotFound();
});

it("update the blog", function () {
    $randomBlog = Blog::inRandomOrder()->first()->id;
    $category = Setup::categoryBuilder();
    $data = [
        'title' => 'Attempt to update a new blog',
        'content' => 'This is a new blog content'
    ];

    $this->putJson("/api/blogs/{$randomBlog}", [
        'title' => $data['title'],
        'content' => $data['content'],
        'categories' => $category
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->where('title', $data['title'])->where('content', $data['content'])->etc());
});

it("failed update the blog (no such category)", function () {
    $randomBlog = Blog::inRandomOrder()->first()->id;
    $category = Setup::categoryBuilder(true);
    $json = [
        'title' => 'Attempt to update a new blog',
        'content' => 'This is a new blog content'
    ];

    $this->putJson("/api/blogs/{$randomBlog}", [
        'title' => $json['title'],
        'content' => $json['content'],
        'categories' => $category
    ])->assertNotFound();
});

it("failed update the blog (not found)", function () {
    $noBlog = Blog::orderByDesc('id')->first()->id + 999;
    $this->putJson("/api/blogs/{$noBlog}", [
        'title' => 'Blog',
        'content' => 'Blog content',
        'categories' => [1, 2, 3]
    ])->assertNotFound();
});

it("delete a blog", function () {
    $randomBlog = Blog::inRandomOrder()->first()->id;
    $this->delete("/api/blogs/{$randomBlog}")->assertOk();
});

it("failed delete a blog (no such blog)", function () {
    $noBlog = Blog::inRandomOrder()->first()->id + 999;
    $this->delete("/api/blogs/{$noBlog}")->assertNotFound();
});

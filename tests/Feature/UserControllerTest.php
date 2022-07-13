<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Base\Setup;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setup::setup();
});

it("shows a list of users", function () {
    $this->get('/api/users')->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
});

it("show a single user", function () {
    $randomUser = User::inRandomOrder()->first()->id;
    $this->get("/api/users/{$randomUser}")->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('name')->has('email')->etc());
});

it("failed show single user (not found)", function () {
    $noUser = User::orderByDesc('id')->first()->id + 999;
    $this->get("/api/users/{$noUser}")->assertStatus(404);
});

it("save a new user", function () {
    $data = [
        'email' => 'newuser@mail.com',
        'name' => 'New User'
    ];

    $this->postJson("/api/users", [
        'email' => $data['email'],
        'name' => $data['name'],
        'password' => "test123456798",
        'password_confirmation' => "test123456798",
        'role' => 'user'
    ])->assertStatus(201)->assertJson((fn (AssertableJson $json) => $json->where('email', $data['email'])->where('name', $data['name'])->etc()));
});

it("failed save a new user (password mismatch)", function () {
    $this->postJson("/api/users", [
        'email' => "test",
        'name' => "test",
        'password' => "test1234567",
        'password_confirmation' => "test123456798",
        'role' => 'user'
    ])->assertStatus(422);
});

it("failed save a new user (role not exist)", function () {
    $this->postJson("/api/users", [
        'email' => "test",
        'name' => "test",
        'password' => "test123456798",
        'password_confirmation' => "test123456798",
        'role' => 'usersssssssssssssssss'
    ])->assertStatus(422);
});

it("update a user without password", function () {
    $randomUser = User::inRandomOrder()->first()->id;

    $data = [
        'email' => 'testupdate@mail.com',
        'name' => 'testupdate'
    ];

    $this->putJson("/api/users/{$randomUser}", [
        'email' => $data['email'],
        'name' => $data['name'],
        'role' => 'user'
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->where('email', $data['email'])->where('name', $data['name'])->etc());
});

it("update a user with password", function () {
    $randomUser = User::inRandomOrder()->first()->id;

    $data = [
        'email' => 'testupdate@mail.com',
        'name' => 'testupdate'
    ];

    $this->putJson("/api/users/{$randomUser}", [
        'email' => $data['email'],
        'name' => $data['name'],
        'password' => "test123456798",
        'password_confirmation' => "test123456798",
        'role' => 'user'
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->where('email', $data['email'])->where('name', $data['name'])->etc());
});

it("failed update a user with password (mismatch)", function () {
    $randomUser = User::inRandomOrder()->first()->id;

    $data = [
        'email' => 'testupdate@mail.com',
        'name' => 'testupdate'
    ];

    $this->putJson("/api/users/{$randomUser}", [
        'email' => $data['email'],
        'name' => $data['name'],
        'password' => "test123456798",
        'password_confirmation' => "test123456",
        'role' => 'user'
    ])->assertStatus(422);
});

it("failed update a user (no such user)", function () {
    $noUser = User::orderByDesc('id')->first()->id + 999;
    $this->putJson("/api/users/{$noUser}", [
        'email' => "test",
        'name' => "test",
        'password' => "test123456798",
        'password_confirmation' => "test123456798",
        'role' => 'user'
    ])->assertStatus(404);
});

it("failed update a user (no such role)", function () {
    $randomUser = User::inRandomOrder()->first()->id;
    $this->putJson("/api/users/{$randomUser}", [
        'email' => "test",
        'name' => "test",
        'password' => "test123456798",
        'password_confirmation' => "test123456798",
        'role' => 'userssssssssssssssssssss'
    ])->assertStatus(422);
});

it("delete a user", function () {
    $randomUser = User::inRandomOrder()->first()->id;
    $this->delete("/api/users/{$randomUser}")->assertOk();
});

it("failed delete a user (no such user)", function () {
    $noUser = User::orderByDesc('id')->first()->id + 999;
    $this->delete("/api/users/{$noUser}")->assertStatus(404);
});

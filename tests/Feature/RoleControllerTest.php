<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Role;
use Tests\Feature\Base\Setup;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setup::setup();
});

it("shows a list of roles", function () {
    $this->get('/api/roles')->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
});

it("show a single role", function () {
    $randomRole = Role::inRandomOrder()->first()->id;
    $this->get("/api/roles/{$randomRole}")->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('name')->etc());
});

it("failed show a single role (no such role)", function () {
    $noRole = Role::orderByDesc('id')->first()->id + 999;
    $this->get("/api/roles/{$noRole}")->assertNotFound();
});

it("save a new role", function () {
    $permission = Setup::permissionBuilder();
    $this->postJson('/api/roles', [
        'name' => 'example_role',
        'permissions' => $permission
    ])->assertCreated()->assertJson(fn (AssertableJson $json) => $json->has('name')->etc());
});

it("failed save a new role (no such permission)", function () {
    $permission = Setup::permissionBuilder(true);
    $this->postJson('/api/roles', [
        'name' => 'example_role',
        'permissions' => $permission
    ])->assertStatus(500);
});

it("failed save a new role (contain whitespaces)", function () {
    $permission = Setup::permissionBuilder();
    $this->postJson('/api/roles', [
        'name' => 'example role',
        'permissions' => $permission
    ])->assertUnprocessable();
});

it("failed save a new role (duplicate)", function () {
    $permission = Setup::permissionBuilder();
    $this->postJson('/api/roles', [
        'name' => 'example_role',
        'permissions' => $permission
    ])->assertCreated();

    $this->postJson('/api/roles', [
        'name' => 'example_role',
        'permissions' => $permission
    ])->assertUnprocessable();
});

it("update a role", function () {
    $role = Role::inRandomOrder()->first();
    $permission = Setup::permissionBuilder();
    $this->putJson("/api/roles/{$role->id}", [
        'name' => 'update_role',
        'permissions' => $permission
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('name')->etc());
});

it("failed update a role (no such permission)", function () {
    $role = Role::inRandomOrder()->first();
    $permission = Setup::permissionBuilder(true);
    $this->putJson("/api/roles/{$role->id}", [
        'name' => 'update_role',
        'permissions' => $permission
    ])->assertStatus(500);
});

it("failed update a role (no such role)", function () {
    $noRole = Role::orderByDesc('id')->first()->id + 999;
    $permission = Setup::permissionBuilder();
    $this->putJson("/api/roles/{$noRole}", [
        'name' => 'update_role',
        'permissions' => $permission
    ])->assertNotFound();
});

it("failed update a role (contain whitespace)", function () {
    $role = Role::inRandomOrder()->first();
    $permission = Setup::permissionBuilder();
    $this->putJson("/api/roles/{$role->id}", [
        'name' => 'update role',
        'permissions' => $permission
    ])->assertUnprocessable();
});

it("failed update a role (duplicate)", function () {
    $role = Role::inRandomOrder()->first();
    $role2 = Role::inRandomOrder()->first();

    while ($role->name === $role2->name) {
        $role2 = Role::inRandomOrder()->first();
    }

    $permission = Setup::permissionBuilder();

    $this->putJson("/api/roles/{$role->id}", [
        'name' => $role2->name,
        'permissions' => $permission
    ])->assertUnprocessable();
});

it("delete a role", function () {
    $randomRole = Role::inRandomOrder()->first()->id;
    $this->delete("/api/roles/{$randomRole}")->assertOk();
});

it("failed delete a role (no such role)", function () {
    $noRole = Role::inRandomOrder()->first()->id + 999;
    $this->delete("/api/roles/{$noRole}")->assertNotFound();
});

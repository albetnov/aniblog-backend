<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\seed;
use function Pest\Laravel\withoutMiddleware;

uses(RefreshDatabase::class);

beforeEach(function () {
    seed(RoleSeeder::class);
    seed(UserSeeder::class);
    withoutMiddleware(ThrottleRequests::class);
});

it("Successfully issue an token", function () {
    $this->postJson("/api/mobile/token", [
        'email' => 'admin@mail.com',
        'password' => 'admin12345',
        'device_name' => 'Siomay Note 7'
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('message')->has('token')->etc());
});

it("Failed issue an token (Validation error)", function () {
    $this->postJson('/api/mobile/token', [
        'email' => 'dummy@mail.com'
    ])->assertUnprocessable();
});

it("Failed issue an token (Cresidentials mismatch)", function () {
    $this->postJson('/api/mobile/token', [
        'email' => 'apani@mail.com',
        'password' => 'idk',
        'device_name' => 'Siomay Note 7'
    ])->assertNotFound();
});

it("Revoke the token", function () {
    $cresidentials = $this->postJson("/api/mobile/token", [
        'email' => 'admin@mail.com',
        'password' => 'admin12345',
        'device_name' => 'Siomay Note 7'
    ])->json()['token'];

    $this->withHeaders([
        'Authorization' => 'Bearer ' . $cresidentials,
        'Accept' => 'application/json'
    ])->delete("/api/mobile/token/revoke")->assertOk();
});

it("Register a new user", function () {
    $this->postJson("/api/mobile/token/new", [
        'email' => 'newacc@mail.com',
        'name' => 'New Acc',
        'password' => 'newacc123',
        'password_confirmation' => 'newacc123',
        'device_name' => 'Siomay Note 7'
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('message')->has('token')->has('user')->etc());
});

it("Failed register a new user (Validation error)", function () {
    $this->postJson("/api/mobile/token/new", [
        'email' => 'test@mail.com'
    ])->assertUnprocessable();
});

it("Update current user successfully", function () {
    Sanctum::actingAs(User::where('email', 'admin@mail.com')->first());

    $this->putJson("/api/mobile/token/edit", [
        'email' => 'testupdate@mail.com',
        'name' => 'testupdate',
        'password' => 'test12345',
        'password_confirmation' => 'test12345'
    ])->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('message')->etc());
});

it("Update current user failed (required field not fulfilled)", function () {
    Sanctum::actingAs(User::where('email', 'admin@mail.com')->first());

    $this->putJson("/api/mobile/token/edit", [
        'name' => 'hello'
    ])->assertUnprocessable();
});

it("Update current user failed (email taken)", function () {
    Sanctum::actingAs(User::where('email', 'admin@mail.com')->first());

    $this->putJson("/api/mobile/token/edit", [
        'name' => 'test',
        'email' => 'asep@mail.com',
        'password' => 'test12345',
        'password_confirmation' => 'test12345'
    ])->assertUnprocessable();
});

it("Update current user failed (password mismatch)", function () {
    Sanctum::actingAs(User::where('email', 'admin@mail.com')->first());

    $this->putJson("/api/mobile/token/edit", [
        'name' => 'test',
        'email' => 'newmail@mail.com',
        'password' => 'test12345',
        'password_confirmation' => 'test123456'
    ])->assertUnprocessable();
});

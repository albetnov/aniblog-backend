<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Base\Setup;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setup::setup();
});

it("Show a list of permissions", function () {
    $this->get('/api/permissions')->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
});

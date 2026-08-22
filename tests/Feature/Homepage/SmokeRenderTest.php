<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    if (! Schema::hasTable('migrations')) {
        Artisan::call('migrate', ['--no-interaction' => true]);
    }
});

it('renders the homepage with no seed data', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('renders the homepage with seeded data', function () {
    if (Schema::hasTable('municipalities') && DB::table('municipalities')->count() === 0) {
        Artisan::call('db:seed', ['--no-interaction' => true]);
    }

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('إذنا', false);
});

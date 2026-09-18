<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('trainees are redirected to the trainee dashboard', function () {
    $user = User::factory()->trainee()->create();
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertRedirect(route('trainee.dashboard'));
});

test('lawyers are redirected to the admin dashboard', function () {
    $user = User::factory()->lawyer()->create();
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
});

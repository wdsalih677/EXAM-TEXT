<?php

use App\Enums\UserRole;
use App\Models\User;

test('guests cannot view trainees', function () {
    $this->get(route('admin.trainees.index'))->assertRedirect(route('login'));
});

test('trainees cannot view the trainee management pages', function () {
    $this->actingAs(User::factory()->trainee()->create())
        ->get(route('admin.trainees.index'))
        ->assertForbidden();
});

test('lawyers can create a trainee with the trainee role', function () {
    $lawyer = User::factory()->lawyer()->create();

    $this->actingAs($lawyer)
        ->post(route('admin.trainees.store'), [
            'name' => 'متدرب جديد',
            'email' => 'new-trainee@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::Lawyer->value,
        ])
        ->assertRedirect(route('admin.trainees.index'));

    $trainee = User::query()->where('email', 'new-trainee@example.com')->first();

    expect($trainee)->not->toBeNull()
        ->and($trainee->role)->toBe(UserRole::Trainee)
        ->and($trainee->name)->toBe('متدرب جديد');
});

test('lawyers can update a trainee', function () {
    $lawyer = User::factory()->lawyer()->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($lawyer)
        ->put(route('admin.trainees.update', $trainee), [
            'name' => 'اسم محدث',
            'email' => $trainee->email,
        ])
        ->assertRedirect(route('admin.trainees.index'));

    expect($trainee->fresh()->name)->toBe('اسم محدث');
});

test('lawyers cannot edit another lawyer through trainee routes', function () {
    $lawyer = User::factory()->lawyer()->create();
    $otherLawyer = User::factory()->lawyer()->create();

    $this->actingAs($lawyer)
        ->get(route('admin.trainees.edit', $otherLawyer))
        ->assertNotFound();
});

test('lawyers can delete a trainee', function () {
    $lawyer = User::factory()->lawyer()->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($lawyer)
        ->delete(route('admin.trainees.destroy', $trainee))
        ->assertRedirect(route('admin.trainees.index'));

    $this->assertDatabaseMissing('users', ['id' => $trainee->id]);
});

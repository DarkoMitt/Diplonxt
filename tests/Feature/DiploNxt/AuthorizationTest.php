<?php

use App\Enums\UserRole;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('verified users see the dashboard for their role', function (UserRole $role, string $text) {
    $user = User::factory()->create(['role' => $role]);
    $this->actingAs($user)->get(route('dashboard'))->assertSuccessful()->assertSee($text);
})->with([
    'student' => [UserRole::Student, 'STUDENT WORKSPACE'],
    'professor' => [UserRole::Professor, 'Professor dashboard'],
    'administrator' => [UserRole::Administrator, 'College overview'],
]);

test('students cannot access administration', function () {
    $student = User::factory()->create(['role' => UserRole::Student]);
    $this->actingAs($student)->get(route('admin.users.index'))->assertForbidden();
});

test('students cannot view another students thesis', function () {
    $student = User::factory()->create(['role' => UserRole::Student]);
    $other = User::factory()->create(['role' => UserRole::Student]);
    $thesis = Thesis::factory()->for($other, 'student')->create();
    $this->actingAs($student)->get(route('theses.show', $thesis))->assertForbidden();
});

<?php

use App\Enums\UserRole;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('student can submit a topic and upload a valid thesis version', function () {
    Storage::fake('local');
    $student = User::factory()->create(['role' => UserRole::Student]);
    $this->actingAs($student)->post(route('theses.store'), ['title' => 'A complete thesis topic', 'description' => str_repeat('A useful and detailed thesis abstract. ', 3)])->assertRedirect();
    $thesis = Thesis::firstOrFail();
    $this->post(route('thesis-versions.store', $thesis), ['document' => UploadedFile::fake()->create('thesis.pdf', 200, 'application/pdf'), 'notes' => 'First draft'])->assertRedirect();
    expect($thesis->versions()->count())->toBe(1);
    Storage::disk('local')->assertExists($thesis->versions()->firstOrFail()->file_path);
});

test('assigned professor can review but another professor cannot', function () {
    $professor = User::factory()->create(['role' => UserRole::Professor]);
    $otherProfessor = User::factory()->create(['role' => UserRole::Professor]);
    $thesis = Thesis::factory()->for($professor, 'professor')->create();
    $this->actingAs($professor)->get(route('theses.show', $thesis))->assertSuccessful();
    $this->actingAs($otherProfessor)->get(route('theses.show', $thesis))->assertForbidden();
});

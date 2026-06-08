<?php

namespace Database\Seeders;

use App\Enums\ThesisStatus;
use App\Enums\UserRole;
use App\Enums\VersionStatus;
use App\Models\User;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@brainster.co'], ['name' => 'Elena Petrova', 'password' => Hash::make('password'), 'role' => UserRole::Administrator, 'email_verified_at' => now()]);
        $professor = User::updateOrCreate(['email' => 'professor@brainster.co'], ['name' => 'Dr. Aleksandar Stojanov', 'password' => Hash::make('password'), 'role' => UserRole::Professor, 'email_verified_at' => now()]);
        $student = User::updateOrCreate(['email' => 'student@brainster.co'], ['name' => 'Mia Nikolovska', 'password' => Hash::make('password'), 'role' => UserRole::Student, 'email_verified_at' => now()]);

        $thesis = $student->thesis()->updateOrCreate([], ['professor_id' => $professor->id, 'title' => 'AI-Powered Personalized Learning Platform', 'description' => 'This thesis explores a personalized learning platform that combines learner progress analytics, adaptive recommendations, and clear educator oversight. The solution focuses on practical product design, responsible use of artificial intelligence, measurable learning outcomes, and a scalable Laravel architecture.', 'status' => ThesisStatus::Development, 'progress_percentage' => 58, 'current_phase' => ThesisStatus::Development->label(), 'deadline' => now()->addWeeks(8)]);

        foreach ([1 => 'Initial research and problem definition.', 2 => 'Added architecture, user research, and prototype findings.', 3 => 'Implemented the core MVP and evaluation plan.'] as $number => $notes) {
            $path = "theses/{$thesis->id}/sample-version-{$number}.pdf";
            Storage::disk('local')->put($path, "DiploNxt sample thesis document version {$number}");
            $thesis->versions()->updateOrCreate(['version_number' => $number], ['uploaded_by' => $student->id, 'file_path' => $path, 'original_file_name' => "personalized-learning-v{$number}.pdf", 'mime_type' => 'application/pdf', 'file_size' => Storage::disk('local')->size($path), 'status' => $number === 3 ? VersionStatus::Pending : VersionStatus::Reviewed, 'notes' => $notes]);
        }
        $version = $thesis->versions()->where('version_number', 2)->firstOrFail();
        $thesis->feedback()->updateOrCreate(['thesis_version_id' => $version->id], ['professor_id' => $professor->id, 'comment' => 'The architecture is clear. Please strengthen the evaluation criteria and connect each success metric to a user research finding.', 'status' => 'open']);
        $thesis->messages()->firstOrCreate(['sender_id' => $professor->id, 'receiver_id' => $student->id, 'message' => 'Great progress on the prototype. Let us review the testing plan this week.']);
        $thesis->messages()->firstOrCreate(['sender_id' => $student->id, 'receiver_id' => $professor->id, 'message' => 'Thank you! I will upload the revised testing plan by Friday.']);
        $thesis->statusHistory()->firstOrCreate(['to_status' => ThesisStatus::Development->value], ['changed_by' => $professor->id, 'from_status' => ThesisStatus::Research->value, 'note' => 'Research milestone approved.']);
        $student->notify(new ThesisActivityNotification('Welcome to DiploNxt', 'Your workspace is ready and your mentor has been assigned.', route('dashboard')));

        User::factory(4)->create(['role' => UserRole::Student]);
        User::factory(2)->create(['role' => UserRole::Professor]);
    }
}

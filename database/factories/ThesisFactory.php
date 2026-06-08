<?php
namespace Database\Factories;
use App\Enums\ThesisStatus;
use App\Enums\UserRole;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @extends Factory<Thesis> */
class ThesisFactory extends Factory
{
    public function definition(): array { return ['student_id' => User::factory()->state(['role' => UserRole::Student]), 'professor_id' => User::factory()->state(['role' => UserRole::Professor]), 'title' => fake()->sentence(6), 'description' => fake()->paragraphs(3, true), 'status' => ThesisStatus::Research, 'progress_percentage' => 32, 'current_phase' => 'Research', 'deadline' => now()->addMonth()]; }
}

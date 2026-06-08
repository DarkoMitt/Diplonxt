<?php

namespace App\Policies;

use App\Models\Thesis;
use App\Models\User;

class ThesisPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function view(User $user, Thesis $thesis): bool
    {
        return $user->id === $thesis->student_id || ($user->isProfessor() && $user->id === $thesis->professor_id);
    }

    public function update(User $user, Thesis $thesis): bool
    {
        return $user->id === $thesis->student_id;
    }

    public function review(User $user, Thesis $thesis): bool
    {
        return $user->isProfessor() && $user->id === $thesis->professor_id;
    }
}

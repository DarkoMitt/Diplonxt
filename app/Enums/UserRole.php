<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case Professor = 'professor';
    case Administrator = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Student',
            self::Professor => 'Professor / Mentor',
            self::Administrator => 'Administrator',
        };
    }
}

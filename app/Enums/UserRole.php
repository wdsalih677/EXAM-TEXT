<?php

namespace App\Enums;

enum UserRole: string
{
    case Lawyer = 'lawyer';
    case Trainee = 'trainee';

    public function label(): string
    {
        return match ($this) {
            self::Lawyer => 'محامٍ',
            self::Trainee => 'متدرب',
        };
    }
}

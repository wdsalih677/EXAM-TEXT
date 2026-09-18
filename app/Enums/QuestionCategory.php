<?php

namespace App\Enums;

enum QuestionCategory: string
{
    case Custody = 'custody';
    case Maintenance = 'maintenance';
    case Divorce = 'divorce';
    case Visitation = 'visitation';

    public function label(): string
    {
        return match ($this) {
            self::Custody => 'حضانة',
            self::Maintenance => 'نفقة',
            self::Divorce => 'طلاق',
            self::Visitation => 'زيارة',
        };
    }
}

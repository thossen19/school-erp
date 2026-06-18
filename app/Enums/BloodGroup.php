<?php

namespace App\Enums;

enum BloodGroup: string
{
    case A_POSITIVE = 'a+';
    case A_NEGATIVE = 'a-';
    case B_POSITIVE = 'b+';
    case B_NEGATIVE = 'b-';
    case AB_POSITIVE = 'ab+';
    case AB_NEGATIVE = 'ab-';
    case O_POSITIVE = 'o+';
    case O_NEGATIVE = 'o-';

    public function label(): string
    {
        return match ($this) {
            self::A_POSITIVE => 'A+',
            self::A_NEGATIVE => 'A-',
            self::B_POSITIVE => 'B+',
            self::B_NEGATIVE => 'B-',
            self::AB_POSITIVE => 'AB+',
            self::AB_NEGATIVE => 'AB-',
            self::O_POSITIVE => 'O+',
            self::O_NEGATIVE => 'O-',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Enums;

enum Religion: string
{
    case HINDUISM = 'hinduism';
    case ISLAM = 'islam';
    case CHRISTIANITY = 'christianity';
    case SIKHISM = 'sikhism';
    case BUDDHISM = 'buddhism';
    case JAINISM = 'jainism';
    case ZOROASTRIANISM = 'zoroastrianism';
    case JUDAISM = 'judaism';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HINDUISM => 'Hinduism',
            self::ISLAM => 'Islam',
            self::CHRISTIANITY => 'Christianity',
            self::SIKHISM => 'Sikhism',
            self::BUDDHISM => 'Buddhism',
            self::JAINISM => 'Jainism',
            self::ZOROASTRIANISM => 'Zoroastrianism',
            self::JUDAISM => 'Judaism',
            self::OTHER => 'Other',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

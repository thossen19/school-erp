<?php

namespace App\Enums;

enum AttendanceType: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case HALF_DAY = 'half_day';
    case HOLIDAY = 'holiday';
    case LEAVE = 'leave';
    case WEEKEND = 'weekend';

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::LATE => 'Late',
            self::HALF_DAY => 'Half Day',
            self::HOLIDAY => 'Holiday',
            self::LEAVE => 'Leave',
            self::WEEKEND => 'Weekend',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

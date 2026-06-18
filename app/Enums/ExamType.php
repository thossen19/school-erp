<?php

namespace App\Enums;

enum ExamType: string
{
    case MIDTERM = 'midterm';
    case FINAL = 'final';
    case QUIZ = 'quiz';
    case ASSIGNMENT = 'assignment';
    case PRACTICAL = 'practical';
    case ORAL = 'oral';
    case PROJECT = 'project';
    case UNIT_TEST = 'unit_test';
    case MONTHLY_TEST = 'monthly_test';
    case PRE_BOARD = 'pre_board';
    case BOARD = 'board';
    case ENTRANCE = 'entrance';
    case WEEKLY_TEST = 'weekly_test';
    case ANNUAL = 'annual';

    public function label(): string
    {
        return match ($this) {
            self::MIDTERM => 'Midterm',
            self::FINAL => 'Final',
            self::QUIZ => 'Quiz',
            self::ASSIGNMENT => 'Assignment',
            self::PRACTICAL => 'Practical',
            self::ORAL => 'Oral',
            self::PROJECT => 'Project',
            self::UNIT_TEST => 'Unit Test',
            self::MONTHLY_TEST => 'Monthly Test',
            self::PRE_BOARD => 'Pre-Board',
            self::BOARD => 'Board',
            self::ENTRANCE => 'Entrance',
            self::WEEKLY_TEST => 'Weekly Test',
            self::ANNUAL => 'Annual',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

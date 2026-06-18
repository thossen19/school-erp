<?php

namespace App\Enums;

enum FeeStatus: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case OVERDUE = 'overdue';
    case WAIVED = 'waived';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PAID => 'Paid',
            self::UNPAID => 'Unpaid',
            self::PARTIAL => 'Partial',
            self::OVERDUE => 'Overdue',
            self::WAIVED => 'Waived',
            self::REFUNDED => 'Refunded',
            self::CANCELLED => 'Cancelled',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

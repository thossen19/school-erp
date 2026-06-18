<?php

namespace App\Enums;

enum AdmissionStatus: string
{
    case APPLIED = 'applied';
    case ENQUIRY = 'enquiry';
    case REGISTERED = 'registered';
    case ADMITTED = 'admitted';
    case REJECTED = 'rejected';
    case WAITING = 'waiting';
    case CANCELLED = 'cancelled';
    case LEFT = 'left';
    case ALUMNI = 'alumni';
    case SUSPENDED = 'suspended';
    case EXPELED = 'expelled';

    public function label(): string
    {
        return match ($this) {
            self::APPLIED => 'Applied',
            self::ENQUIRY => 'Enquiry',
            self::REGISTERED => 'Registered',
            self::ADMITTED => 'Admitted',
            self::REJECTED => 'Rejected',
            self::WAITING => 'Waiting',
            self::CANCELLED => 'Cancelled',
            self::LEFT => 'Left',
            self::ALUMNI => 'Alumni',
            self::SUSPENDED => 'Suspended',
            self::EXPELED => 'Expelled',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

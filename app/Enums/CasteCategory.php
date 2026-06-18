<?php

namespace App\Enums;

enum CasteCategory: string
{
    case GENERAL = 'general';
    case OBC = 'obc';
    case SC = 'sc';
    case ST = 'st';
    case EWS = 'ews';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL => 'General',
            self::OBC => 'OBC',
            self::SC => 'SC',
            self::ST => 'ST',
            self::EWS => 'EWS',
            self::OTHER => 'Other',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

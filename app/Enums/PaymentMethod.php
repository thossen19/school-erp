<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CHEQUE = 'cheque';
    case BANK_TRANSFER = 'bank_transfer';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case ONLINE = 'online';
    case UPI = 'upi';
    case MOBILE_WALLET = 'mobile_wallet';
    case DD = 'dd';
    case NEFT = 'neft';
    case RTGS = 'rtgs';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::CHEQUE => 'Cheque',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CREDIT_CARD => 'Credit Card',
            self::DEBIT_CARD => 'Debit Card',
            self::ONLINE => 'Online',
            self::UPI => 'UPI',
            self::MOBILE_WALLET => 'Mobile Wallet',
            self::DD => 'Demand Draft',
            self::NEFT => 'NEFT',
            self::RTGS => 'RTGS',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}

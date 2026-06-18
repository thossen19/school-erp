<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case SCHOOL_ADMIN = 'school_admin';
    case PRINCIPAL = 'principal';
    case VICE_PRINCIPAL = 'vice_principal';
    case TEACHER = 'teacher';
    case ACCOUNTANT = 'accountant';
    case HR_MANAGER = 'hr_manager';
    case LIBRARIAN = 'librarian';
    case TRANSPORT_MANAGER = 'transport_manager';
    case HOSTEL_WARDEN = 'hostel_warden';
    case PARENT = 'parent';
    case STUDENT = 'student';
    case ALUMNI = 'alumni';
    case RECEPTIONIST = 'receptionist';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::SCHOOL_ADMIN => 'School Admin',
            self::PRINCIPAL => 'Principal',
            self::VICE_PRINCIPAL => 'Vice Principal',
            self::TEACHER => 'Teacher',
            self::ACCOUNTANT => 'Accountant',
            self::HR_MANAGER => 'HR Manager',
            self::LIBRARIAN => 'Librarian',
            self::TRANSPORT_MANAGER => 'Transport Manager',
            self::HOSTEL_WARDEN => 'Hostel Warden',
            self::PARENT => 'Parent',
            self::STUDENT => 'Student',
            self::ALUMNI => 'Alumni',
            self::RECEPTIONIST => 'Receptionist',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function schoolRoles(): array
    {
        return [
            self::SCHOOL_ADMIN,
            self::PRINCIPAL,
            self::VICE_PRINCIPAL,
            self::TEACHER,
            self::ACCOUNTANT,
        ];
    }
}

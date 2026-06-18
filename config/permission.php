<?php

return [

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'role_has_permissions' => 'role_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'model_has_permissions' => 'model_has_permissions',
        'roles' => 'roles',
        'permissions' => 'permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'school_id',
        'team_models' => [App\Models\School::class, App\Models\Branch::class],
    ],

    'register_permission_check_method' => true,
    'register_guard_checked_permissions' => true,

    /*
    |--------------------------------------------------------------------------
    | Module-Based Permissions
    |--------------------------------------------------------------------------
    | Each module has: list, view, create, edit, delete, restore, force_delete
    */
    'modules' => [
        'students',
        'teachers',
        'staff',
        'classes',
        'sections',
        'subjects',
        'timetable',
        'attendance',
        'exams',
        'grades',
        'assignments',
        'fees',
        'payments',
        'accounts',
        'payroll',
        'library',
        'transport',
        'hostel',
        'inventory',
        'admissions',
        'parents',
        'communication',
        'notifications',
        'reports',
        'settings',
        'users',
        'roles',
        'permissions',
        'academic_years',
        'holidays',
        'events',
        'gallery',
        'alumni',
        'documents',
        'audit_logs',
        'backup',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Permissions Matrix
    |--------------------------------------------------------------------------
    | Defines which permissions each role has by default.
    | 'all' = full access, 'view' = read-only, 'none' = no access
    | Customize as needed.
    */
    'role_permissions' => [
        \App\Enums\UserRole::SUPER_ADMIN->value => [
            'all' => '*',
        ],

        \App\Enums\UserRole::SCHOOL_ADMIN->value => [
            'all' => [
                'students', 'teachers', 'staff', 'classes', 'sections',
                'subjects', 'timetable', 'attendance', 'exams', 'grades',
                'assignments', 'fees', 'payments', 'accounts', 'payroll',
                'library', 'transport', 'hostel', 'inventory', 'admissions',
                'parents', 'communication', 'notifications', 'reports',
                'settings', 'users', 'academic_years', 'holidays', 'events',
                'gallery', 'alumni', 'documents',
            ],
        ],

        \App\Enums\UserRole::PRINCIPAL->value => [
            'all' => [
                'students', 'teachers', 'staff', 'classes', 'sections',
                'subjects', 'timetable', 'attendance', 'exams', 'grades',
                'assignments', 'library', 'transport', 'hostel', 'inventory',
                'admissions', 'parents', 'communication', 'notifications',
                'reports', 'holidays', 'events', 'gallery', 'alumni', 'documents',
            ],
            'view' => ['accounts', 'payroll', 'fees', 'payments'],
            'none' => ['settings', 'users', 'roles', 'permissions', 'audit_logs', 'backup'],
        ],

        \App\Enums\UserRole::VICE_PRINCIPAL->value => [
            'all' => [
                'students', 'teachers', 'staff', 'classes', 'sections',
                'subjects', 'timetable', 'attendance', 'exams', 'grades',
                'assignments', 'library', 'transport', 'hostel', 'inventory',
                'admissions', 'parents', 'communication', 'notifications',
                'reports', 'holidays', 'events', 'gallery', 'alumni', 'documents',
            ],
            'view' => ['fees', 'payments', 'accounts', 'payroll'],
            'none' => ['settings', 'users', 'roles', 'permissions', 'audit_logs', 'backup'],
        ],

        \App\Enums\UserRole::TEACHER->value => [
            'all' => [
                'students', 'attendance', 'exams', 'grades', 'assignments',
                'subjects', 'timetable', 'communication', 'notifications',
                'events', 'gallery', 'documents',
            ],
            'view' => ['classes', 'sections', 'library', 'reports', 'holidays'],
            'none' => [
                'teachers', 'staff', 'fees', 'payments', 'accounts', 'payroll',
                'transport', 'hostel', 'inventory', 'admissions', 'parents',
                'settings', 'users', 'roles', 'permissions', 'audit_logs',
                'backup', 'alumni',
            ],
        ],

        \App\Enums\UserRole::ACCOUNTANT->value => [
            'all' => ['fees', 'payments', 'accounts'],
            'view' => ['students', 'classes', 'sections', 'reports'],
            'none' => [
                'teachers', 'staff', 'attendance', 'exams', 'grades',
                'assignments', 'timetable', 'library', 'transport', 'hostel',
                'inventory', 'admissions', 'parents', 'communication',
                'notifications', 'settings', 'users', 'roles', 'permissions',
                'audit_logs', 'backup', 'alumni', 'events', 'gallery',
                'documents', 'subjects', 'payroll', 'holidays',
            ],
        ],

        \App\Enums\UserRole::HR_MANAGER->value => [
            'all' => ['teachers', 'staff', 'payroll', 'attendance'],
            'view' => ['students', 'classes', 'sections', 'reports', 'documents'],
            'none' => [
                'fees', 'payments', 'accounts', 'exams', 'grades',
                'assignments', 'timetable', 'library', 'transport', 'hostel',
                'inventory', 'admissions', 'parents', 'communication',
                'notifications', 'settings', 'users', 'roles', 'permissions',
                'audit_logs', 'backup', 'subjects', 'holidays', 'events',
                'gallery', 'alumni',
            ],
        ],

        \App\Enums\UserRole::LIBRARIAN->value => [
            'all' => ['library'],
            'view' => ['students', 'teachers', 'staff', 'classes', 'sections', 'reports'],
            'none' => [
                'fees', 'payments', 'accounts', 'payroll', 'exams', 'grades',
                'assignments', 'timetable', 'attendance', 'transport', 'hostel',
                'inventory', 'admissions', 'parents', 'communication',
                'notifications', 'settings', 'users', 'roles', 'permissions',
                'audit_logs', 'backup', 'subjects', 'holidays', 'events',
                'gallery', 'alumni', 'documents',
            ],
        ],

        \App\Enums\UserRole::TRANSPORT_MANAGER->value => [
            'all' => ['transport', 'students', 'staff'],
            'view' => ['classes', 'sections', 'reports'],
            'none' => [
                'teachers', 'fees', 'payments', 'accounts', 'payroll',
                'exams', 'grades', 'assignments', 'timetable', 'attendance',
                'library', 'hostel', 'inventory', 'admissions', 'parents',
                'communication', 'notifications', 'settings', 'users',
                'roles', 'permissions', 'audit_logs', 'backup', 'subjects',
                'holidays', 'events', 'gallery', 'alumni', 'documents',
            ],
        ],

        \App\Enums\UserRole::HOSTEL_WARDEN->value => [
            'all' => ['hostel', 'students', 'staff', 'inventory'],
            'view' => ['classes', 'sections', 'reports'],
            'none' => [
                'teachers', 'fees', 'payments', 'accounts', 'payroll',
                'exams', 'grades', 'assignments', 'timetable', 'attendance',
                'library', 'transport', 'admissions', 'parents',
                'communication', 'notifications', 'settings', 'users',
                'roles', 'permissions', 'audit_logs', 'backup', 'subjects',
                'holidays', 'events', 'gallery', 'alumni', 'documents',
            ],
        ],

        \App\Enums\UserRole::PARENT->value => [
            'view' => [
                'students', 'attendance', 'exams', 'grades', 'assignments',
                'timetable', 'fees', 'payments', 'events', 'gallery',
                'communication', 'notifications', 'holidays',
            ],
            'all' => ['documents'],
            'none' => '*',
        ],

        \App\Enums\UserRole::STUDENT->value => [
            'view' => [
                'attendance', 'exams', 'grades', 'assignments', 'timetable',
                'events', 'gallery', 'communication', 'notifications',
                'holidays', 'library',
            ],
            'none' => '*',
        ],

        \App\Enums\UserRole::ALUMNI->value => [
            'view' => ['events', 'gallery', 'communication', 'notifications', 'alumni'],
            'none' => '*',
        ],

        \App\Enums\UserRole::RECEPTIONIST->value => [
            'all' => ['communication', 'notifications', 'events'],
            'create' => ['students', 'parents', 'admissions', 'visitors'],
            'view' => [
                'students', 'teachers', 'staff', 'classes', 'sections',
                'timetable', 'holidays', 'events', 'gallery', 'documents',
            ],
            'none' => [
                'fees', 'payments', 'accounts', 'payroll', 'exams', 'grades',
                'assignments', 'attendance', 'library', 'transport', 'hostel',
                'inventory', 'settings', 'users', 'roles', 'permissions',
                'audit_logs', 'backup', 'subjects', 'reports',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Generated Permission Names
    |--------------------------------------------------------------------------
    | These are auto-generated from the module list above.
    */
    'permission_names' => [
        'list', 'view', 'create', 'edit', 'delete', 'restore', 'force_delete',
    ],

];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | School Information
    |--------------------------------------------------------------------------
    */
    'name' => env('SCHOOL_NAME', 'My School'),
    'code' => env('SCHOOL_CODE', 'SCH001'),
    'address' => env('SCHOOL_ADDRESS', ''),
    'phone' => env('SCHOOL_PHONE', ''),
    'email' => env('SCHOOL_EMAIL', ''),
    'website' => env('SCHOOL_WEBSITE', ''),
    'logo' => env('SCHOOL_LOGO', ''),
    'timezone' => env('SCHOOL_TIMEZONE', 'Asia/Kolkata'),
    'locale' => env('SCHOOL_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    */
    'multi_tenant' => env('SCHOOL_MULTI_TENANT', true),
    'school_id_column' => 'school_id',

    /*
    |--------------------------------------------------------------------------
    | Academic Settings
    |--------------------------------------------------------------------------
    */
    'academic' => [
        'default_year_start' => '04-01',
        'default_year_end' => '03-31',
        'session_format' => 'Y-Y', // e.g., 2024-2025
        'terms' => [
            'enabled' => true,
            'max_terms' => 3,
            'names' => ['Term 1', 'Term 2', 'Term 3'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admission Settings
    |--------------------------------------------------------------------------
    */
    'admission' => [
        'enable_online' => env('SCHOOL_ADMISSION_ONLINE', true),
        'require_guardian' => true,
        'max_applications_per_student' => 3,
        'age_limits' => [
            'min_years' => 3,
            'max_years' => 18,
        ],
        'required_documents' => [
            'birth_certificate',
            'photograph',
            'previous_report_card',
            'transfer_certificate',
            'address_proof',
        ],
        'registration_fee' => env('SCHOOL_REGISTRATION_FEE', 500),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fee Settings
    |--------------------------------------------------------------------------
    */
    'fee' => [
        'currency' => env('SCHOOL_CURRENCY', 'INR'),
        'currency_symbol' => env('SCHOOL_CURRENCY_SYMBOL', '₹'),
        'decimal_places' => 2,
        'enable_online_payment' => env('SCHOOL_ENABLE_ONLINE_PAYMENT', true),
        'enable_partial_payment' => env('SCHOOL_ENABLE_PARTIAL_PAYMENT', true),
        'enable_installment' => env('SCHOOL_ENABLE_INSTALLMENT', true),
        'max_installments' => 4,
        'due_date_reminder_days' => 7,
        'late_fee_per_day' => env('SCHOOL_LATE_FEE_PER_DAY', 10),
        'late_fee_after_days' => env('SCHOOL_LATE_FEE_AFTER_DAYS', 15),
        'concession' => [
            'max_percentage' => 100,
            'require_approval' => true,
            'approval_roles' => ['super_admin', 'school_admin', 'principal'],
        ],
        'payment_methods' => [
            'cash',
            'cheque',
            'bank_transfer',
            'online',
            'upi',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attendance Settings
    |--------------------------------------------------------------------------
    */
    'attendance' => [
        'marking_method' => env('SCHOOL_ATTENDANCE_METHOD', 'daily'), // daily, subject_wise, period_wise
        'enable_biometric' => env('SCHOOL_ENABLE_BIOMETRIC', false),
        'enable_face_recognition' => env('SCHOOL_ENABLE_FACE_RECOGNITION', false),
        'enable_geolocation' => env('SCHOOL_ENABLE_GEOLOCATION', false),
        'auto_mark_holidays' => true,
        'auto_mark_weekends' => true,
        'grace_period_minutes' => 15,
        'half_day_hours' => 4,
        'working_days_per_week' => 6,
        'allow_self_attendance' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Grade / Mark Settings
    |--------------------------------------------------------------------------
    */
    'grade' => [
        'system' => env('SCHOOL_GRADE_SYSTEM', 'percentage'), // percentage, gpa, letter_grade
        'pass_percentage' => env('SCHOOL_PASS_PERCENTAGE', 33),
        'gpa_max' => 10,
        'grade_scale' => [
            ['min' => 91, 'max' => 100, 'grade' => 'A+', 'gpa' => 10.0],
            ['min' => 81, 'max' => 90, 'grade' => 'A', 'gpa' => 9.0],
            ['min' => 71, 'max' => 80, 'grade' => 'B+', 'gpa' => 8.0],
            ['min' => 61, 'max' => 70, 'grade' => 'B', 'gpa' => 7.0],
            ['min' => 51, 'max' => 60, 'grade' => 'C+', 'gpa' => 6.0],
            ['min' => 41, 'max' => 50, 'grade' => 'C', 'gpa' => 5.0],
            ['min' => 33, 'max' => 40, 'grade' => 'D', 'gpa' => 4.0],
            ['min' => 0, 'max' => 32, 'grade' => 'F', 'gpa' => 0.0],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exam Settings
    |--------------------------------------------------------------------------
    */
    'exam' => [
        'enable_grade_book' => true,
        'enable_combined_marks' => true,
        'max_marks_per_subject' => 100,
        'passing_marks_percentage' => env('SCHOOL_PASS_PERCENTAGE', 33),
        'allow_reevaluation' => env('SCHOOL_ALLOW_REEVALUATION', true),
        'reevaluation_fee' => env('SCHOOL_REEVALUATION_FEE', 500),
    ],

    /*
    |--------------------------------------------------------------------------
    | Timetable Settings
    |--------------------------------------------------------------------------
    */
    'timetable' => [
        'period_duration_minutes' => 45,
        'break_duration_minutes' => 15,
        'lunch_duration_minutes' => 30,
        'max_periods_per_day' => 8,
        'enable_rotating_timetable' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Library Settings
    |--------------------------------------------------------------------------
    */
    'library' => [
        'enable' => true,
        'max_books_per_student' => 3,
        'max_books_per_teacher' => 5,
        'issue_duration_days' => 14,
        'fine_per_day' => env('SCHOOL_LIBRARY_FINE_PER_DAY', 5),
        'enable_renewals' => true,
        'max_renewals' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Transport Settings
    |--------------------------------------------------------------------------
    */
    'transport' => [
        'enable' => true,
        'enable_route_tracking' => env('SCHOOL_ENABLE_ROUTE_TRACKING', false),
        'enable_gps_tracking' => env('SCHOOL_ENABLE_GPS_TRACKING', false),
        'max_students_per_vehicle' => 40,
        'fee_calculation' => 'distance_based', // distance_based, flat, zone_based
    ],

    /*
    |--------------------------------------------------------------------------
    | Hostel Settings
    |--------------------------------------------------------------------------
    */
    'hostel' => [
        'enable' => env('SCHOOL_ENABLE_HOSTEL', false),
        'enable_meal_management' => true,
        'enable_visitor_log' => true,
        'occupancy_check_interval_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | HR / Staff Settings
    |--------------------------------------------------------------------------
    */
    'hr' => [
        'enable' => true,
        'enable_attendance' => true,
        'enable_payroll' => env('SCHOOL_ENABLE_PAYROLL', true),
        'enable_leave_management' => true,
        'payslip_generation_day' => 1,
        'leave_types' => [
            'casual' => 12,
            'sick' => 10,
            'annual' => 15,
            'maternity' => 180,
            'paternity' => 15,
            'bereavement' => 3,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notification' => [
        'channels' => ['mail', 'sms', 'push', 'database'],
        'enable_sms' => env('SCHOOL_ENABLE_SMS', false),
        'enable_email' => true,
        'enable_push' => env('SCHOOL_ENABLE_PUSH', true),
        'sms_provider' => env('SCHOOL_SMS_PROVIDER', 'twilio'),
        'sms_api_key' => env('SCHOOL_SMS_API_KEY', ''),
        'sender_id' => env('SCHOOL_SMS_SENDER_ID', 'SCHOOL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache & Performance
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'prefix' => 'school_',
        'ttl' => env('SCHOOL_CACHE_TTL', 3600),
        'enable_module_cache' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Status
    |--------------------------------------------------------------------------
    */
    'modules' => [
        'admission' => true,
        'student' => true,
        'teacher' => true,
        'parent' => true,
        'attendance' => true,
        'exam' => true,
        'grade' => true,
        'timetable' => true,
        'fee' => true,
        'account' => true,
        'payroll' => env('SCHOOL_ENABLE_PAYROLL', true),
        'library' => true,
        'transport' => env('SCHOOL_ENABLE_TRANSPORT', true),
        'hostel' => env('SCHOOL_ENABLE_HOSTEL', false),
        'inventory' => true,
        'communication' => true,
        'notification' => true,
        'report' => true,
        'hr' => true,
        'alumni' => true,
        'event' => true,
        'gallery' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'online_admission' => env('FEATURE_ONLINE_ADMISSION', true),
        'online_fee_payment' => env('FEATURE_ONLINE_FEE_PAYMENT', true),
        'biometric_attendance' => env('FEATURE_BIOMETRIC_ATTENDANCE', false),
        'sms_notification' => env('FEATURE_SMS_NOTIFICATION', false),
        'email_notification' => env('FEATURE_EMAIL_NOTIFICATION', true),
        'whatsapp_notification' => env('FEATURE_WHATSAPP_NOTIFICATION', false),
        'gps_tracking' => env('FEATURE_GPS_TRACKING', false),
        'face_recognition' => env('FEATURE_FACE_RECOGNITION', false),
        'bulk_sms' => env('FEATURE_BULK_SMS', false),
        'bulk_email' => env('FEATURE_BULK_EMAIL', true),
        'auto_fee_reminder' => env('FEATURE_AUTO_FEE_REMINDER', true),
        'parent_portal' => env('FEATURE_PARENT_PORTAL', true),
        'student_portal' => env('FEATURE_STUDENT_PORTAL', true),
        'teacher_portal' => env('FEATURE_TEACHER_PORTAL', true),
    ],

];

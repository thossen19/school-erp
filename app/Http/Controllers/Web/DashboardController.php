<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $schoolId = 1;
        $today = now()->toDateString();

        // Student Statistics
        $totalStudents = DB::table('students')->where('school_id', $schoolId)->count();
        $activeStudents = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->count();
        $maleStudents = DB::table('students')->where('school_id', $schoolId)->where('gender', 'male')->count();
        $femaleStudents = DB::table('students')->where('school_id', $schoolId)->where('gender', 'female')->count();
        $newAdmissions = DB::table('students')->where('school_id', $schoolId)->where('is_new', true)->count();
        $studentGenderData = collect([
            ['label' => 'Boys', 'value' => $maleStudents, 'color' => 'primary'],
            ['label' => 'Girls', 'value' => $femaleStudents, 'color' => 'success'],
        ]);
        $classWiseStudents = DB::table('students')->where('students.school_id', $schoolId)
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name')
            ->get();

        // Admission Statistics
        $totalEnquiries = DB::table('admission_enquiries')->where('school_id', $schoolId)->count();
        $pendingEnquiries = DB::table('admission_enquiries')->where('school_id', $schoolId)->where('status', 'pending')->count();
        $convertedEnquiries = DB::table('admission_enquiries')->where('school_id', $schoolId)->where('status', 'converted')->count();
        $totalForms = DB::table('admission_forms')->where('school_id', $schoolId)->count();
        $approvedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'approved')->count();
        $admittedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'admitted')->count();

        // Attendance Analytics
        $todayAttendance = DB::table('attendances')->where('school_id', $schoolId)->whereDate('date', $today)->count();
        $todayPresent = DB::table('attendances')->where('school_id', $schoolId)->whereDate('date', $today)->where('status', 'present')->count();
        $todayAttendancePct = $todayAttendance > 0 ? round(($todayPresent / $todayAttendance) * 100) : 0;
        $weeklyAttendance = DB::table('attendances')->where('school_id', $schoolId)
            ->whereBetween('date', [now()->subDays(7)->toDateString(), $today])
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'), DB::raw("SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present"))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get();

        // Fee Collection
        $totalFeesCollected = DB::table('fee_collections')->where('school_id', $schoolId)->sum('paid_amount');
        $monthlyFees = DB::table('fee_collections')->where('school_id', $schoolId)
            ->whereYear('payment_date', now()->year)
            ->select(DB::raw('MONTH(payment_date) as month'), DB::raw('SUM(paid_amount) as total'))
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->orderBy('month')
            ->get();
        $pendingDues = DB::table('fee_due_trackings')->where('school_id', $schoolId)->where('status', 'pending')->sum('balance');
        $collectionMethods = DB::table('fee_collections')->where('school_id', $schoolId)
            ->select('payment_method', DB::raw('SUM(paid_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Payroll Summary
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentPayroll = DB::table('payrolls')->where('school_id', $schoolId)
            ->where('month', $currentMonth)->where('year', $currentYear)
            ->select(DB::raw('SUM(net_salary) as total'), DB::raw('COUNT(*) as count'))
            ->first();
        $pendingPayroll = DB::table('payrolls')->where('school_id', $schoolId)
            ->where('status', 'pending')->sum('net_salary');
        $monthlyPayroll = DB::table('payrolls')->where('school_id', $schoolId)->where('year', $currentYear)
            ->select(DB::raw('month'), DB::raw('SUM(net_salary) as total'))
            ->groupBy('month')->orderBy('month')->get();

        // Academic Performance
        $totalExams = DB::table('exams')->where('school_id', $schoolId)->count();
        $avgPercentage = DB::table('exam_results')->where('school_id', $schoolId)->avg('percentage');
        $passCount = DB::table('exam_results')->where('school_id', $schoolId)->where('is_absent', false)
            ->where(DB::raw('COALESCE(percentage,0)'), '>=', 40)->count();
        $totalResults = DB::table('exam_results')->where('school_id', $schoolId)->where('is_absent', false)->count();
        $passRate = $totalResults > 0 ? round(($passCount / $totalResults) * 100) : 0;

        // Transport
        $totalVehicles = DB::table('transport_vehicles')->where('school_id', $schoolId)->count();
        $activeVehicles = DB::table('transport_vehicles')->where('school_id', $schoolId)->where('status', 'active')->count();
        $totalRoutes = DB::table('transport_routes')->where('school_id', $schoolId)->count();
        $transportAllocations = DB::table('transport_allocations')->where('school_id', $schoolId)->count();
        $vehicleStatuses = DB::table('transport_vehicles')->where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();

        // Inventory
        $totalItems = DB::table('items')->where('school_id', $schoolId)->count();
        $lowStockItems = DB::table('items')->where('school_id', $schoolId)
            ->whereColumn('quantity', '<=', 'min_quantity')->where('min_quantity', '>', 0)->count();
        $totalStockValue = DB::table('items')->where('school_id', $schoolId)
            ->select(DB::raw('SUM(quantity * price) as total'))->first()->total ?? 0;
        $recentMovements = DB::table('stock_movements')->where('school_id', $schoolId)
            ->orderBy('movement_date', 'desc')->take(5)->get();

        // Library
        $totalBooks = DB::table('books')->where('school_id', $schoolId)->sum('quantity');
        $availableBooks = DB::table('books')->where('school_id', $schoolId)->sum('available_quantity');
        $issuedBooks = DB::table('book_issues')->where('school_id', $schoolId)->where('status', 'issued')->count();
        $libraryMembers = DB::table('library_members')->where('school_id', $schoolId)->where('is_active', true)->count();

        // Hostel Occupancy
        $totalBeds = DB::table('hostel_beds')
            ->join('hostel_rooms', 'hostel_beds.hostel_room_id', '=', 'hostel_rooms.id')
            ->join('hostels', 'hostel_rooms.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)
            ->count();
        $occupiedBeds = DB::table('hostel_allocations')->where('school_id', $schoolId)->where('status', 'active')->count();
        $hostelOccupancyPct = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;
        $totalHostels = DB::table('hostels')->where('school_id', $schoolId)->count();

        // Upcoming Events
        $upcomingEvents = DB::table('events')->where('school_id', $schoolId)
            ->where('start_date', '>=', $today)
            ->whereIn('status', ['published', 'upcoming'])
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Notifications
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->count();

        // AI Insights
        $aiInsights = collect();
        if ($todayAttendancePct < 75) {
            $aiInsights->push(['icon' => 'fa-exclamation-triangle', 'color' => 'danger', 'title' => 'Low Attendance Alert', 'text' => "Today's attendance is {$todayAttendancePct}%, below the 75% threshold. Consider sending reminders."]);
        }
        if ($pendingDues > 0) {
            $aiInsights->push(['icon' => 'fa-money-bill-wave', 'color' => 'warning', 'title' => 'Pending Fee Dues', 'text' => '₹' . number_format($pendingDues, 2) . ' in pending fee dues. ' . ($pendingDues > 100000 ? 'Escalation recommended.' : 'Follow up with defaulters.')]);
        }
        if ($lowStockItems > 0) {
            $aiInsights->push(['icon' => 'fa-boxes', 'color' => 'warning', 'title' => 'Low Stock Items', 'text' => "{$lowStockItems} item(s) are below minimum stock level. Restock soon."]);
        }
        if ($issuedBooks > 0) {
            $overdueIssues = DB::table('book_issues')->where('school_id', $schoolId)->where('status', 'issued')->where('due_date', '<', $today)->count();
            if ($overdueIssues > 0) {
                $aiInsights->push(['icon' => 'fa-book', 'color' => 'info', 'title' => 'Overdue Books', 'text' => "{$overdueIssues} book(s) are overdue. Send return reminders."]);
            }
        }
        if ($hostelOccupancyPct > 90) {
            $aiInsights->push(['icon' => 'fa-bed', 'color' => 'info', 'title' => 'High Hostel Occupancy', 'text' => "Hostel occupancy is at {$hostelOccupancyPct}%. Consider expanding capacity for next year."]);
        }
        if ($passRate > 0 && $passRate < 60) {
            $aiInsights->push(['icon' => 'fa-graduation-cap', 'color' => 'danger', 'title' => 'Academic Performance', 'text' => "Pass rate is {$passRate}%. Remedial classes recommended."]);
        }
        if ($aiInsights->isEmpty()) {
            $aiInsights->push(['icon' => 'fa-check-circle', 'color' => 'success', 'title' => 'All Systems Normal', 'text' => 'Key metrics are within acceptable ranges. No action required.']);
        }

        $data = compact(
            'totalStudents', 'activeStudents', 'maleStudents', 'femaleStudents', 'newAdmissions',
            'studentGenderData', 'classWiseStudents',
            'totalEnquiries', 'pendingEnquiries', 'convertedEnquiries', 'totalForms', 'approvedForms', 'admittedForms',
            'todayAttendance', 'todayPresent', 'todayAttendancePct', 'weeklyAttendance',
            'totalFeesCollected', 'monthlyFees', 'pendingDues', 'collectionMethods',
            'currentPayroll', 'pendingPayroll', 'monthlyPayroll',
            'totalExams', 'avgPercentage', 'passRate',
            'totalVehicles', 'activeVehicles', 'totalRoutes', 'transportAllocations', 'vehicleStatuses',
            'totalItems', 'lowStockItems', 'totalStockValue', 'recentMovements',
            'totalBooks', 'availableBooks', 'issuedBooks', 'libraryMembers',
            'totalBeds', 'occupiedBeds', 'hostelOccupancyPct', 'totalHostels',
            'upcomingEvents',
            'notifications', 'unreadCount',
            'aiInsights'
        );

        return view('dashboard.index', ['data' => $data, 'user' => $user]);
    }
}

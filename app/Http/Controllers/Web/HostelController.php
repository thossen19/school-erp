<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HostelController extends Controller
{
    private function schoolId()
    {
        return session('school_id', 1);
    }

    // ============ Hostel Setup ============

    public function index(Request $request)
    {
        $hostels = DB::table('hostels')
            ->where('school_id', $this->schoolId())
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('name', 'like', "%{$v}%")->orWhere('code', 'like', "%{$v}%");
            }))
            ->when($request->status !== null, fn($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate(20);

        return view('hostels.index', compact('hostels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|string|in:boys,girls,coed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'warden_id' => 'nullable|integer',
            'total_rooms' => 'nullable|integer|min:0',
            'total_beds' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $validated['school_id'] = $this->schoolId();
        $validated['status'] = $request->boolean('status', true);
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(substr($validated['name'], 0, 3)) . rand(100, 999);
        }

        DB::table('hostels')->insert($validated);
        return redirect()->route('hostels.index')->with('success', 'Hostel created');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|string|in:boys,girls,coed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'warden_id' => 'nullable|integer',
            'total_rooms' => 'nullable|integer|min:0',
            'total_beds' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->boolean('status', true);
        DB::table('hostels')->where('id', $id)->update($validated + ['updated_at' => now()]);
        return redirect()->route('hostels.index')->with('success', 'Hostel updated');
    }

    public function destroy(int $id)
    {
        DB::table('hostels')->where('id', $id)->delete();
        return redirect()->route('hostels.index')->with('success', 'Hostel deleted');
    }

    // ============ Room Allocation ============

    public function allocations(Request $request)
    {
        $allocations = DB::table('hostel_allocations')
            ->leftJoin('students', 'hostel_allocations.student_id', '=', 'students.id')
            ->leftJoin('hostels', 'hostel_allocations.hostel_id', '=', 'hostels.id')
            ->leftJoin('hostel_rooms', 'hostel_allocations.room_id', '=', 'hostel_rooms.id')
            ->leftJoin('hostel_beds', 'hostel_allocations.bed_id', '=', 'hostel_beds.id')
            ->where('hostel_allocations.school_id', $this->schoolId())
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('students.admission_no', 'like', "%{$v}%");
            }))
            ->when($request->status, fn($q, $v) => $q->where('hostel_allocations.status', $v))
            ->when($request->hostel_id, fn($q, $v) => $q->where('hostel_allocations.hostel_id', $v))
            ->select(
                'hostel_allocations.*',
                'students.first_name', 'students.last_name', 'students.admission_no',
                'hostels.name as hostel_name',
                'hostel_rooms.room_number',
                'hostel_beds.bed_number'
            )
            ->orderBy('hostel_allocations.check_in_date', 'desc')
            ->paginate(20);

        $hostels = DB::table('hostels')->where('school_id', $this->schoolId())->where('status', true)->orderBy('name')->get(['id', 'name']);
        $students = DB::table('students')->where('school_id', $this->schoolId())->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);
        $rooms = DB::table('hostel_rooms')->where('status', true)->orderBy('room_number')->get(['id', 'hostel_id', 'room_number', 'capacity']);
        $beds = DB::table('hostel_beds')->where('status', 'available')->orderBy('bed_number')->get(['id', 'hostel_room_id', 'bed_number']);

        return view('hostels.allocations', compact('allocations', 'hostels', 'students', 'rooms', 'beds'));
    }

    public function storeAllocation(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'hostel_id' => 'required|integer|exists:hostels,id',
            'room_id' => 'required|integer|exists:hostel_rooms,id',
            'bed_id' => 'nullable|integer|exists:hostel_beds,id',
            'check_in_date' => 'required|date',
            'expected_checkout_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,checked_out',
        ]);

        $validated['school_id'] = $this->schoolId();
        $validated['status'] ??= 'active';
        $validated['check_out_date'] = null;

        DB::table('hostel_allocations')->insert($validated);
        if ($validated['bed_id']) {
            DB::table('hostel_beds')->where('id', $validated['bed_id'])->update(['status' => 'occupied']);
        }

        return redirect()->route('hostels.allocations')->with('success', 'Room allocated');
    }

    public function updateAllocation(Request $request, int $id)
    {
        $allocation = DB::table('hostel_allocations')->where('id', $id)->first();

        $validated = $request->validate([
            'check_out_date' => 'nullable|date',
            'expected_checkout_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,checked_out',
        ]);

        DB::table('hostel_allocations')->where('id', $id)->update($validated + ['updated_at' => now()]);

        if (($validated['status'] ?? $allocation->status) === 'checked_out' && $allocation->bed_id) {
            DB::table('hostel_beds')->where('id', $allocation->bed_id)->update(['status' => 'available']);
        }

        return redirect()->route('hostels.allocations')->with('success', 'Allocation updated');
    }

    public function deleteAllocation(int $id)
    {
        $allocation = DB::table('hostel_allocations')->where('id', $id)->first();
        if ($allocation && $allocation->bed_id) {
            DB::table('hostel_beds')->where('id', $allocation->bed_id)->update(['status' => 'available']);
        }
        DB::table('hostel_allocations')->where('id', $id)->delete();
        return redirect()->route('hostels.allocations')->with('success', 'Allocation deleted');
    }

    // ============ Bed Management ============

    public function beds(Request $request)
    {
        $beds = DB::table('hostel_beds')
            ->leftJoin('hostel_rooms', 'hostel_beds.hostel_room_id', '=', 'hostel_rooms.id')
            ->leftJoin('hostels', 'hostel_rooms.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $this->schoolId())
            ->when($request->status, fn($q, $v) => $q->where('hostel_beds.status', $v))
            ->when($request->hostel_id, fn($q, $v) => $q->where('hostels.id', $v))
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('hostel_beds.bed_number', 'like', "%{$v}%")
                  ->orWhere('hostel_rooms.room_number', 'like', "%{$v}%");
            }))
            ->select('hostel_beds.*', 'hostel_rooms.room_number', 'hostel_rooms.hostel_id', 'hostels.name as hostel_name')
            ->orderBy('hostels.name')->orderBy('hostel_rooms.room_number')->orderBy('hostel_beds.bed_number')
            ->paginate(20);

        $hostels = DB::table('hostels')->where('school_id', $this->schoolId())->where('status', true)->orderBy('name')->get(['id', 'name']);

        return view('hostels.beds', compact('beds', 'hostels'));
    }

    public function updateBed(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:available,occupied,maintenance',
        ]);

        DB::table('hostel_beds')->where('id', $id)->update($validated + ['updated_at' => now()]);
        return redirect()->route('hostels.beds')->with('success', 'Bed status updated');
    }

    // ============ Hostel Fees ============

    public function fees(Request $request)
    {
        $fees = DB::table('hostel_fees')
            ->leftJoin('hostels', 'hostel_fees.hostel_id', '=', 'hostels.id')
            ->leftJoin('academic_years', 'hostel_fees.academic_year_id', '=', 'academic_years.id')
            ->where('hostel_fees.school_id', $this->schoolId())
            ->when($request->hostel_id, fn($q, $v) => $q->where('hostel_fees.hostel_id', $v))
            ->select('hostel_fees.*', 'hostels.name as hostel_name', 'academic_years.title as academic_year')
            ->orderBy('hostels.name')
            ->paginate(20);

        $hostels = DB::table('hostels')->where('school_id', $this->schoolId())->where('status', true)->orderBy('name')->get(['id', 'name']);
        $academicYears = DB::table('academic_years')->orderBy('name', 'desc')->get(['id', 'name']);

        return view('hostels.fees', compact('fees', 'hostels', 'academicYears'));
    }

    public function storeFee(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'room_type' => 'nullable|string|max:50',
            'fee_amount' => 'required|numeric|min:0',
            'frequency' => 'nullable|string|in:monthly,quarterly,half_yearly,yearly,one_time',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'name' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = $this->schoolId();
        $validated['frequency'] ??= 'monthly';

        DB::table('hostel_fees')->insert($validated);
        return redirect()->route('hostels.fees')->with('success', 'Fee structure added');
    }

    public function updateFee(Request $request, int $id)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'room_type' => 'nullable|string|max:50',
            'fee_amount' => 'required|numeric|min:0',
            'frequency' => 'nullable|string|in:monthly,quarterly,half_yearly,yearly,one_time',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'name' => 'nullable|string|max:255',
        ]);

        $validated['frequency'] ??= 'monthly';

        DB::table('hostel_fees')->where('id', $id)->update($validated + ['updated_at' => now()]);
        return redirect()->route('hostels.fees')->with('success', 'Fee structure updated');
    }

    public function deleteFee(int $id)
    {
        DB::table('hostel_fees')->where('id', $id)->delete();
        return redirect()->route('hostels.fees')->with('success', 'Fee structure deleted');
    }

    // ============ Visitor Tracking ============

    public function visitors(Request $request)
    {
        $visitors = DB::table('hostel_visitors')
            ->leftJoin('hostels', 'hostel_visitors.hostel_id', '=', 'hostels.id')
            ->leftJoin('students', 'hostel_visitors.student_id', '=', 'students.id')
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('hostel_visitors.visitor_name', 'like', "%{$v}%")
                  ->orWhere('hostel_visitors.visitor_phone', 'like', "%{$v}%")
                  ->orWhere('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%");
            }))
            ->when($request->hostel_id, fn($q, $v) => $q->where('hostel_visitors.hostel_id', $v))
            ->select(
                'hostel_visitors.*',
                'hostels.name as hostel_name',
                'students.first_name', 'students.last_name', 'students.admission_no'
            )
            ->orderBy('hostel_visitors.check_in', 'desc')
            ->paginate(20);

        $hostels = DB::table('hostels')->where('school_id', $this->schoolId())->where('status', true)->orderBy('name')->get(['id', 'name']);
        $students = DB::table('students')->where('school_id', $this->schoolId())->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);

        return view('hostels.visitors', compact('visitors', 'hostels', 'students'));
    }

    public function storeVisitor(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'student_id' => 'required|integer|exists:students,id',
            'relation' => 'required|string|max:50',
            'purpose' => 'nullable|string|max:255',
            'check_in' => 'required|date',
        ]);

        DB::table('hostel_visitors')->insert($validated);
        return redirect()->route('hostels.visitors')->with('success', 'Visitor entry added');
    }

    public function updateVisitor(Request $request, int $id)
    {
        $validated = $request->validate([
            'check_out' => 'required|date',
        ]);

        DB::table('hostel_visitors')->where('id', $id)->update($validated + ['updated_at' => now()]);
        return redirect()->route('hostels.visitors')->with('success', 'Visitor check-out recorded');
    }

    public function deleteVisitor(int $id)
    {
        DB::table('hostel_visitors')->where('id', $id)->delete();
        return redirect()->route('hostels.visitors')->with('success', 'Visitor entry deleted');
    }

    // ============ Leave Management ============

    public function leaves(Request $request)
    {
        $leaves = DB::table('hostel_leave')
            ->leftJoin('hostels', 'hostel_leave.hostel_id', '=', 'hostels.id')
            ->leftJoin('students', 'hostel_leave.student_id', '=', 'students.id')
            ->when($request->status, fn($q, $v) => $q->where('hostel_leave.status', $v))
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('students.admission_no', 'like', "%{$v}%");
            }))
            ->select(
                'hostel_leave.*',
                'hostels.name as hostel_name',
                'students.first_name', 'students.last_name', 'students.admission_no'
            )
            ->orderBy('hostel_leave.created_at', 'desc')
            ->paginate(20);

        $hostels = DB::table('hostels')->where('school_id', $this->schoolId())->where('status', true)->orderBy('name')->get(['id', 'name']);
        $students = DB::table('students')->where('school_id', $this->schoolId())->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);

        return view('hostels.leaves', compact('leaves', 'hostels', 'students'));
    }

    public function storeLeave(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'student_id' => 'required|integer|exists:students,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string',
        ]);

        $validated['status'] = 'pending';
        $validated['parent_approval'] = false;
        $validated['warden_approval'] = false;

        DB::table('hostel_leave')->insert($validated);
        return redirect()->route('hostels.leaves')->with('success', 'Leave request submitted');
    }

    public function updateLeave(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $data = $validated + ['updated_at' => now()];
        if ($validated['status'] === 'approved') {
            $data['warden_approval'] = true;
        }

        DB::table('hostel_leave')->where('id', $id)->update($data);
        return redirect()->route('hostels.leaves')->with('success', 'Leave ' . $validated['status']);
    }

    public function deleteLeave(int $id)
    {
        DB::table('hostel_leave')->where('id', $id)->delete();
        return redirect()->route('hostels.leaves')->with('success', 'Leave request deleted');
    }

    // ============ Hostel Reports ============

    public function reports()
    {
        $schoolId = $this->schoolId();

        $totalHostels = DB::table('hostels')->where('school_id', $schoolId)->count();
        $totalRooms = DB::table('hostel_rooms')
            ->join('hostels', 'hostel_rooms.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)->count();
        $totalBeds = DB::table('hostel_beds')
            ->join('hostel_rooms', 'hostel_beds.hostel_room_id', '=', 'hostel_rooms.id')
            ->join('hostels', 'hostel_rooms.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)->count();
        $activeAllocations = DB::table('hostel_allocations')->where('school_id', $schoolId)->where('status', 'active')->count();
        $pendingLeaves = DB::table('hostel_leave')
            ->join('hostels', 'hostel_leave.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)
            ->where('hostel_leave.status', 'pending')->count();

        $hostelOccupancy = DB::table('hostels')
            ->where('school_id', $schoolId)
            ->select('name', 'total_rooms', 'total_beds')
            ->orderBy('name')
            ->get();

        $recentAllocations = DB::table('hostel_allocations')
            ->leftJoin('students', 'hostel_allocations.student_id', '=', 'students.id')
            ->leftJoin('hostels', 'hostel_allocations.hostel_id', '=', 'hostels.id')
            ->leftJoin('hostel_rooms', 'hostel_allocations.room_id', '=', 'hostel_rooms.id')
            ->where('hostel_allocations.school_id', $schoolId)
            ->where('hostel_allocations.status', 'active')
            ->select('hostel_allocations.*', 'students.first_name', 'students.last_name', 'hostels.name as hostel_name', 'hostel_rooms.room_number')
            ->orderBy('hostel_allocations.check_in_date', 'desc')
            ->limit(10)
            ->get();

        $leaveStats = DB::table('hostel_leave')
            ->join('hostels', 'hostel_leave.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)
            ->select('hostel_leave.status', DB::raw('count(*) as total'))
            ->groupBy('hostel_leave.status')
            ->get();

        $bedStatusCounts = DB::table('hostel_beds')
            ->join('hostel_rooms', 'hostel_beds.hostel_room_id', '=', 'hostel_rooms.id')
            ->join('hostels', 'hostel_rooms.hostel_id', '=', 'hostels.id')
            ->where('hostels.school_id', $schoolId)
            ->select('hostel_beds.status', DB::raw('count(*) as total'))
            ->groupBy('hostel_beds.status')
            ->get();

        return view('hostels.reports', compact(
            'totalHostels', 'totalRooms', 'totalBeds', 'activeAllocations', 'pendingLeaves',
            'hostelOccupancy', 'recentAllocations', 'leaveStats', 'bedStatusCounts'
        ));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontOfficeController extends Controller
{
    public function dashboard()
    {
        $todayVisitors = DB::table('visitors')->where('school_id', 1)->whereDate('visit_date', now()->toDateString())->count();
        $pendingAppointments = DB::table('appointments')->where('school_id', 1)->where('status', 'pending')->whereDate('date', '>=', now()->toDateString())->count();
        $openEnquiries = DB::table('enquiries')->where('school_id', 1)->where('status', 'open')->count();
        $openComplaints = DB::table('complaints')->where('school_id', 1)->where('status', 'open')->count();
        $todayCalls = DB::table('call_logs')->where('school_id', 1)->whereDate('created_at', now()->toDateString())->count();
        $recentVisitors = DB::table('visitors')->where('school_id', 1)->orderBy('created_at', 'desc')->take(5)->get();
        $todayAppointments = DB::table('appointments')->where('school_id', 1)->whereDate('date', now()->toDateString())->orderBy('time')->get();

        return view('front-office.dashboard', compact(
            'todayVisitors', 'pendingAppointments', 'openEnquiries', 'openComplaints', 'todayCalls',
            'recentVisitors', 'todayAppointments'
        ));
    }

    public function visitors(Request $request)
    {
        $query = DB::table('visitors')->where('school_id', 1);
        if ($request->filled('date_from')) $query->whereDate('visit_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('visit_date', '<=', $request->date_to);
        if ($request->filled('purpose')) $query->where('purpose', 'like', '%'.$request->purpose.'%');
        $visitors = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('front-office.visitors', compact('visitors'));
    }

    public function storeVisitor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'purpose' => 'required|string|max:255',
            'whom_to_meet' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
            'check_in' => 'required',
            'id_card_number' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:5000',
        ]);
        $validated['school_id'] = 1;
        DB::table('visitors')->insert($validated);
        return redirect()->back()->with('success', 'Visitor added successfully');
    }

    public function updateVisitor(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'purpose' => 'required|string|max:255',
            'whom_to_meet' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'id_card_number' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:5000',
        ]);
        DB::table('visitors')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->back()->with('success', 'Visitor updated successfully');
    }

    public function deleteVisitor(int $id)
    {
        DB::table('visitors')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Visitor deleted');
    }

    public function enquiries(Request $request)
    {
        $query = DB::table('enquiries')->where('school_id', 1);
        if ($request->filled('type')) $query->where('enquiry_type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);
        $enquiries = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('front-office.enquiries', compact('enquiries'));
    }

    public function storeEnquiry(Request $request)
    {
        $validated = $request->validate([
            'enquirer_name' => 'required|string|max:255',
            'enquirer_phone' => 'required|string|max:20',
            'enquirer_email' => 'nullable|email|max:255',
            'enquiry_type' => 'required|string|max:100',
            'message' => 'nullable|string|max:5000',
            'source' => 'nullable|string|max:50',
            'assigned_to' => 'nullable|string|max:255',
            'follow_up_date' => 'nullable|date',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'open';
        DB::table('enquiries')->insert($validated);
        return redirect()->back()->with('success', 'Enquiry submitted');
    }

    public function updateEnquiry(Request $request, int $id)
    {
        $validated = $request->validate([
            'enquirer_name' => 'required|string|max:255',
            'enquirer_phone' => 'required|string|max:20',
            'enquirer_email' => 'nullable|email|max:255',
            'enquiry_type' => 'required|string|max:100',
            'message' => 'nullable|string|max:5000',
            'source' => 'nullable|string|max:50',
            'assigned_to' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'response' => 'nullable|string|max:5000',
            'follow_up_date' => 'nullable|date',
        ]);
        DB::table('enquiries')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->back()->with('success', 'Enquiry updated');
    }

    public function deleteEnquiry(int $id)
    {
        DB::table('enquiries')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Enquiry deleted');
    }

    public function callLogs(Request $request)
    {
        $query = DB::table('call_logs')->where('school_id', 1);
        if ($request->filled('call_type')) $query->where('call_type', $request->call_type);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);
        $callLogs = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('front-office.call-logs', compact('callLogs'));
    }

    public function storeCallLog(Request $request)
    {
        $validated = $request->validate([
            'caller_name' => 'required|string|max:255',
            'caller_phone' => 'required|string|max:20',
            'callee_name' => 'nullable|string|max:255',
            'callee_phone' => 'nullable|string|max:20',
            'call_type' => 'required|string|in:incoming,outgoing',
            'duration' => 'nullable|integer|min:0',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'received_by' => 'nullable|string|max:255',
            'follow_up_date' => 'nullable|date',
        ]);
        $validated['school_id'] = 1;
        DB::table('call_logs')->insert($validated);
        return redirect()->back()->with('success', 'Call log added');
    }

    public function updateCallLog(Request $request, int $id)
    {
        $validated = $request->validate([
            'caller_name' => 'required|string|max:255',
            'caller_phone' => 'required|string|max:20',
            'callee_name' => 'nullable|string|max:255',
            'callee_phone' => 'nullable|string|max:20',
            'call_type' => 'required|string|in:incoming,outgoing',
            'duration' => 'nullable|integer|min:0',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'received_by' => 'nullable|string|max:255',
            'follow_up_date' => 'nullable|date',
        ]);
        DB::table('call_logs')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->back()->with('success', 'Call log updated');
    }

    public function deleteCallLog(int $id)
    {
        DB::table('call_logs')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Call log deleted');
    }

    public function appointments(Request $request)
    {
        $query = DB::table('appointments')->where('appointments.school_id', 1)
            ->leftJoin('users', 'appointments.staff_id', '=', 'users.id')
            ->select('appointments.*', 'users.name as staff_name');
        if ($request->filled('status')) $query->where('appointments.status', $request->status);
        if ($request->filled('date')) $query->whereDate('appointments.date', $request->date);
        $appointments = $query->orderBy('appointments.date', 'desc')->orderBy('appointments.time', 'desc')->paginate(15);
        return view('front-office.appointments', compact('appointments'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'visitor_email' => 'nullable|email|max:255',
            'staff_id' => 'nullable|integer|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
            'purpose' => 'required|string|max:255',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'pending';
        DB::table('appointments')->insert($validated);
        return redirect()->back()->with('success', 'Appointment scheduled');
    }

    public function updateAppointment(Request $request, int $id)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'visitor_email' => 'nullable|email|max:255',
            'staff_id' => 'nullable|integer|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
            'purpose' => 'required|string|max:255',
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
            'end_time' => 'nullable',
        ]);
        DB::table('appointments')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->back()->with('success', 'Appointment updated');
    }

    public function deleteAppointment(int $id)
    {
        DB::table('appointments')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Appointment deleted');
    }

    public function complaints(Request $request)
    {
        $query = DB::table('complaints')->where('school_id', 1);
        if ($request->filled('type')) $query->where('complaint_type', $request->type);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('status')) $query->where('status', $request->status);
        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('front-office.complaints', compact('complaints'));
    }

    public function storeComplaint(Request $request)
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255',
            'complainant_phone' => 'required|string|max:20',
            'complainant_email' => 'nullable|email|max:255',
            'complaint_type' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'assigned_to' => 'nullable|string|max:255',
            'priority' => 'required|string|in:low,medium,high,critical',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'open';
        DB::table('complaints')->insert($validated);
        return redirect()->back()->with('success', 'Complaint registered');
    }

    public function updateComplaint(Request $request, int $id)
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:255',
            'complainant_phone' => 'required|string|max:20',
            'complainant_email' => 'nullable|email|max:255',
            'complaint_type' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'assigned_to' => 'nullable|string|max:255',
            'priority' => 'required|string|in:low,medium,high,critical',
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'resolution' => 'nullable|string|max:5000',
            'resolution_notes' => 'nullable|string|max:5000',
        ]);
        $data = $validated;
        if ($data['status'] === 'resolved') {
            $data['resolved_at'] = now();
        }
        DB::table('complaints')->where('id', $id)->where('school_id', 1)->update($data);
        return redirect()->back()->with('success', 'Complaint updated');
    }

    public function deleteComplaint(int $id)
    {
        DB::table('complaints')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Complaint deleted');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function create()
    {
        $schoolId = 1;
        $students = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();
        $categories = DB::table('fee_categories')->where('school_id', $schoolId)->where('status', 1)->get();
        $structures = DB::table('fee_structures')
            ->leftJoin('fee_categories', 'fee_structures.fee_category_id', '=', 'fee_categories.id')
            ->leftJoin('classes', 'fee_structures.class_id', '=', 'classes.id')
            ->where('fee_structures.school_id', $schoolId)
            ->where('fee_structures.status', 1)
            ->select('fee_structures.*', 'fee_categories.name as category_name', 'classes.name as class_name')
            ->get();
        return view('fees.create', compact('students', 'categories', 'structures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'fee_category_id' => 'required|integer|exists:fee_categories,id',
            'fee_structure_id' => 'required|integer|exists:fee_structures,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:cash,cheque,online_transfer,bank_deposit,card',
            'payment_mode' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:125',
            'cheque_no' => 'nullable|string|max:125',
            'payment_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:125|unique:fee_collections,receipt_number',
            'remarks' => 'nullable|string|max:500',
        ]);

        $totalAmount = $validated['amount'];
        $discount = $validated['discount_amount'] ?? 0;
        $fine = $validated['fine_amount'] ?? 0;
        $paidAmount = $validated['paid_amount'];
        $balance = $totalAmount - $paidAmount - $discount + $fine;
        $status = $balance <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'pending');

        DB::table('fee_collections')->insert([
            'school_id' => 1,
            'student_id' => $validated['student_id'],
            'fee_structure_id' => $validated['fee_structure_id'],
            'amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'discount_amount' => $discount,
            'fine_amount' => $fine,
            'total_amount' => $totalAmount + $fine - $discount,
            'balance_amount' => max(0, $balance),
            'payment_method' => $validated['payment_method'],
            'payment_mode' => $validated['payment_mode'] ?? $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'cheque_no' => $validated['cheque_no'] ?? null,
            'payment_date' => $validated['payment_date'],
            'receipt_number' => $validated['receipt_number'] ?? ('RCT-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)),
            'status' => $status,
            'remarks' => $validated['remarks'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('fees.fee-collection')->with('success', 'Fee collected successfully');
    }

    public function show(int $id)
    {
        $record = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->leftJoin('fee_categories', 'fee_structures.fee_category_id', '=', 'fee_categories.id')
            ->where('fee_collections.id', $id)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no',
                'fee_structures.name as structure_name', 'fee_categories.name as category_name')
            ->first();

        if (!$record) abort(404);
        return view('fees.show', compact('record'));
    }

    public function destroy(int $id)
    {
        DB::table('fee_collections')->where('id', $id)->delete();
        return redirect()->route('fees.fee-collection')->with('success', 'Fee record deleted');
    }

    public function feeStructure(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_structures')
            ->leftJoin('fee_categories', 'fee_structures.fee_category_id', '=', 'fee_categories.id')
            ->leftJoin('classes', 'fee_structures.class_id', '=', 'classes.id')
            ->where('fee_structures.school_id', $schoolId)
            ->select('fee_structures.*', 'fee_categories.name as category_name', 'classes.name as class_name');

        if ($request->class_id) $query->where('fee_structures.class_id', $request->class_id);
        if ($request->fee_category_id) $query->where('fee_structures.fee_category_id', $request->fee_category_id);

        $structures = $query->orderBy('fee_structures.name')->paginate(50);
        $categories = DB::table('fee_categories')->where('school_id', $schoolId)->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('fees.fee-structure', compact('structures', 'categories', 'classes'));
    }

    public function feeCategories(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_categories')->where('school_id', $schoolId);

        if ($request->status !== null) $query->where('status', $request->status);

        $categories = $query->orderBy('name')->paginate(50);
        return view('fees.fee-categories', compact('categories'));
    }

    public function installmentPlans(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_installments')
            ->leftJoin('fee_structures', 'fee_installments.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_installments.school_id', $schoolId)
            ->select('fee_installments.*', 'fee_structures.name as structure_name');

        if ($request->fee_structure_id) $query->where('fee_installments.fee_structure_id', $request->fee_structure_id);
        if ($request->status) $query->where('fee_installments.status', $request->status);

        $installments = $query->orderBy('fee_structures.name')->orderBy('fee_installments.order')->paginate(50);
        $structures = DB::table('fee_structures')->where('school_id', $schoolId)->where('is_installment', 1)->get();

        return view('fees.installment-plans', compact('installments', 'structures'));
    }

    public function scholarshipManagement(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('scholarships')->where('school_id', $schoolId);

        if ($request->status !== null) $query->where('status', $request->status);
        if ($request->type) $query->where('type', $request->type);

        $scholarships = $query->orderBy('name')->paginate(50);
        return view('fees.scholarship-management', compact('scholarships'));
    }

    public function discountManagement(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_discounts')->where('school_id', $schoolId);

        if ($request->status !== null) $query->where('status', $request->status);
        if ($request->type) $query->where('type', $request->type);

        $discounts = $query->orderBy('name')->paginate(50);
        return view('fees.discount-management', compact('discounts'));
    }

    public function fineManagement(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->where('fee_collections.fine_amount', '>', 0)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->date_from) $query->whereDate('fee_collections.payment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('fee_collections.payment_date', '<=', $request->date_to);
        if ($request->student_id) $query->where('fee_collections.student_id', $request->student_id);

        $fines = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(50);
        return view('fees.fine-management', compact('fines'));
    }

    public function feeCollection(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->date_from) $query->whereDate('fee_collections.payment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('fee_collections.payment_date', '<=', $request->date_to);
        if ($request->status) $query->where('fee_collections.status', $request->status);
        if ($request->payment_mode) $query->where('fee_collections.payment_mode', $request->payment_mode);

        $collections = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(50);
        return view('fees.fee-collection', compact('collections'));
    }

    public function onlinePayment(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->where(function($q) {
                $q->where('fee_collections.payment_method', 'online_transfer')
                  ->orWhere('fee_collections.payment_mode', 'online')
                  ->orWhere('fee_collections.payment_method', 'card');
            })
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->date_from) $query->whereDate('fee_collections.payment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('fee_collections.payment_date', '<=', $request->date_to);
        if ($request->status) $query->where('fee_collections.status', $request->status);

        $payments = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(50);
        $totalOnline = (clone $query)->count();
        $totalAmount = (clone $query)->sum('paid_amount');

        return view('fees.online-payment', compact('payments', 'totalOnline', 'totalAmount'));
    }

    public function receiptGeneration(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->whereNotNull('fee_collections.receipt_number')
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->date_from) $query->whereDate('fee_collections.payment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('fee_collections.payment_date', '<=', $request->date_to);
        if ($request->receipt_no) $query->where('fee_collections.receipt_number', 'like', '%'.$request->receipt_no.'%');

        $receipts = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(50);
        return view('fees.receipt-generation', compact('receipts'));
    }

    public function dueTracking(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->status) $query->where('fee_collections.status', $request->status);
        else $query->whereIn('fee_collections.status', ['partial', 'pending']);
        if ($request->student_id) $query->where('fee_collections.student_id', $request->student_id);

        $dues = $query->orderBy('fee_collections.payment_date', 'asc')->paginate(50);
        return view('fees.due-tracking', compact('dues'));
    }

    public function autoReminder(Request $request)
    {
        $schoolId = 1;
        $dueFees = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->whereIn('fee_collections.status', ['partial', 'pending'])
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name')
            ->orderBy('fee_collections.payment_date', 'asc')
            ->get();

        $overdueCount = $dueFees->where('status', 'pending')->count();
        $partialCount = $dueFees->where('status', 'partial')->count();
        $totalDue = $dueFees->sum('balance_amount');

        return view('fees.auto-reminder', compact('dueFees', 'overdueCount', 'partialCount', 'totalDue'));
    }

    public function paymentReconciliation(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_collections.school_id', $schoolId)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'fee_structures.name as structure_name');

        if ($request->date_from) $query->whereDate('fee_collections.payment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('fee_collections.payment_date', '<=', $request->date_to);
        if ($request->payment_method) $query->where('fee_collections.payment_method', $request->payment_method);

        $reconciliations = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(50);

        $summary = DB::table('fee_collections')
            ->where('school_id', $schoolId)
            ->when($request->date_from, fn($q) => $q->whereDate('payment_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('payment_date', '<=', $request->date_to))
            ->select('payment_method', DB::raw('count(*) as total_transactions'), DB::raw('sum(paid_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get();

        return view('fees.payment-reconciliation', compact('reconciliations', 'summary'));
    }

    public function financialReports(Request $request)
    {
        $schoolId = 1;

        $totalCollected = DB::table('fee_collections')->where('school_id', $schoolId)->where('status', 'paid')->sum('paid_amount');
        $totalPending = DB::table('fee_collections')->where('school_id', $schoolId)->whereIn('status', ['partial', 'pending'])->sum('balance_amount');
        $totalFineCollected = DB::table('fee_collections')->where('school_id', $schoolId)->sum('fine_amount');
        $totalDiscountGiven = DB::table('fee_collections')->where('school_id', $schoolId)->sum('discount_amount');
        $totalTransactions = DB::table('fee_collections')->where('school_id', $schoolId)->count();

        $monthlyCollection = DB::table('fee_collections')
            ->where('school_id', $schoolId)
            ->where('status', 'paid')
            ->select(DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"), DB::raw('sum(paid_amount) as total'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $methodBreakdown = DB::table('fee_collections')
            ->where('school_id', $schoolId)
            ->select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(paid_amount) as amount'))
            ->groupBy('payment_method')
            ->get();

        return view('fees.financial-reports', compact(
            'totalCollected', 'totalPending', 'totalFineCollected',
            'totalDiscountGiven', 'totalTransactions', 'monthlyCollection', 'methodBreakdown'
        ));
    }

    public function printReceipt(int $id)
    {
        $receipt = DB::table('fee_collections')
            ->leftJoin('students', 'fee_collections.student_id', '=', 'students.id')
            ->leftJoin('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->leftJoin('fee_categories', 'fee_structures.fee_category_id', '=', 'fee_categories.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'students.section_id', '=', 'sections.id')
            ->where('fee_collections.id', $id)
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no',
                'students.roll_number', 'classes.name as class_name', 'sections.name as section_name',
                'fee_structures.name as structure_name', 'fee_categories.name as category_name')
            ->first();

        if (!$receipt) abort(404);

        $school = DB::table('schools')->where('id', 1)->first();

        return view('fees.print-receipt', compact('receipt', 'school'));
    }
}

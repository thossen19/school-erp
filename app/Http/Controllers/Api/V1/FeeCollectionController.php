<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeeCollectionRequest;
use App\Models\Fee\FeeCollection;
use App\Services\Fee\FeeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeCollectionController extends Controller
{
    use ApiResponseTrait;

    protected FeeService $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request): JsonResponse
    {
        $collections = FeeCollection::with('student:id,first_name,last_name,admission_no', 'feeCategory', 'feeStructure')->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->category_id, fn($q) => $q->where('fee_category_id', $request->category_id))->when($request->payment_method, fn($q) => $q->where('payment_method', $request->payment_method))->when($request->date_from, fn($q) => $q->whereDate('payment_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('payment_date', '<=', $request->date_to))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('payment_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($collections, 'Fee collections retrieved');
    }

    public function store(StoreFeeCollectionRequest $request): JsonResponse
    {
        $collection = $this->feeService->collectPayment($request->validated());
        return $this->createdResponse($collection->load('student', 'feeCategory', 'feeStructure'), 'Fee collected');
    }

    public function generateReceipt(int $id): JsonResponse
    {
        $collection = FeeCollection::with('student', 'feeCategory', 'feeStructure', 'installment')->findOrFail($id);
        $receipt = $this->feeService->generateReceipt($collection);
        return $this->successResponse($receipt, 'Receipt generated');
    }

    public function getByStudent(int $studentId, Request $request): JsonResponse
    {
        $collections = FeeCollection::with('feeCategory', 'feeStructure')->where('student_id', $studentId)->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('payment_date', 'desc')->get();

        $totalPaid = $collections->sum('paid_amount');
        $totalDue = $collections->sum('balance_amount');

        return $this->successResponse([
            'collections' => $collections,
            'summary' => [
                'total_paid' => $totalPaid,
                'total_due' => $totalDue,
                'total_fees' => $totalPaid + $totalDue,
            ],
        ], 'Fee collections by student');
    }

    public function getDuePayments(Request $request): JsonResponse
    {
        $duePayments = $this->feeService->getDuePayments(
            $request->get('school_id'),
            $request->get('academic_year_id'),
            $request->class_id,
            $request->student_id
        );
        return $this->successResponse($duePayments, 'Due payments retrieved');
    }

    public function getReport(Request $request): JsonResponse
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $report = $this->feeService->getFeeReport(
            $request->date_from,
            $request->date_to,
            $request->get('school_id'),
            $request->category_id
        );

        return $this->successResponse($report, 'Fee report generated');
    }

    public function reconcilePayment(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'reconciled_at' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $collection = FeeCollection::findOrFail($id);
        $collection->update(array_merge($request->validated(), ['is_reconciled' => true]));

        return $this->successResponse($collection, 'Payment reconciled');
    }

    public function sendReminder(int $id): JsonResponse
    {
        $result = $this->feeService->sendFeeReminder($id);
        return $this->successResponse($result, 'Reminder sent');
    }
}

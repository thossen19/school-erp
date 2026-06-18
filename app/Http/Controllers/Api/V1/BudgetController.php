<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Budget;
use App\Services\Accounting\BudgetService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    use ApiResponseTrait;

    protected BudgetService $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    public function index(Request $request): JsonResponse
    {
        $budgets = Budget::with('account')->when($request->fiscal_year, fn($q) => $q->where('fiscal_year', $request->fiscal_year))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('fiscal_year', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($budgets, 'Budgets retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:chart_of_accounts,id',
            'fiscal_year' => 'required|string|max:20',
            'allocated_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:draft,approved,active,closed',
        ]);

        $budget = Budget::create($validated);
        return $this->createdResponse($budget->load('account'), 'Budget created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Budget::with('account')->findOrFail($id),
            'Budget retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $budget = Budget::findOrFail($id);
        $validated = $request->validate([
            'allocated_amount' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:draft,approved,active,closed',
        ]);
        $budget->update($validated);
        return $this->updatedResponse($budget->fresh()->load('account'), 'Budget updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Budget::findOrFail($id)->delete();
        return $this->deletedResponse('Budget deleted');
    }

    public function trackExpenditure(int $id, Request $request): JsonResponse
    {
        $tracking = $this->budgetService->trackExpenditure($id);
        return $this->successResponse($tracking, 'Budget expenditure tracking');
    }
}

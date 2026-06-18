<?php

namespace App\Services\Accounting;

use App\Contracts\RepositoryInterface;
use App\Models\Accounting\Budget;
use App\Repositories\Accounting\BudgetRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class BudgetService extends BaseService
{
    protected BudgetRepository $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        parent::__construct();
        $this->budgetRepository = $budgetRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->budgetRepository;
    }

    public function createBudget(array $data): Budget
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';
            $budget = $this->budgetRepository->create($data);

            $this->logActivity('budget_created', $budget);

            return $budget;
        });
    }

    public function trackExpenditure(int $budgetId, float $amount, string $description): Budget
    {
        return DB::transaction(function () use ($budgetId, $amount, $description) {
            $budget = $this->budgetRepository->getById($budgetId);

            $newSpent = ($budget->actual_spent ?? 0) + $amount;

            $budget = $this->budgetRepository->update($budgetId, [
                'actual_spent' => $newSpent,
            ]);

            $this->logActivity('budget_expenditure_tracked', [
                'budget_id' => $budgetId,
                'amount' => $amount,
                'description' => $description,
            ]);

            return $budget;
        });
    }

    public function getBudgetReport(int $budgetId): array
    {
        $utilization = $this->budgetRepository->getBudgetUtilization($budgetId);

        $report = [
            'budget_id' => $budgetId,
            'allocated' => $utilization['allocated'],
            'spent' => $utilization['spent'],
            'remaining' => $utilization['remaining'],
            'utilization_percentage' => $utilization['utilization_percentage'],
            'status' => $utilization['remaining'] < 0 ? 'over_budget' : 'on_track',
        ];

        $this->logActivity('budget_report_viewed', $report);

        return $report;
    }
}

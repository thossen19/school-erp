<?php

namespace App\Services\Hr;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Hr\Employee;
use App\Repositories\Hr\EmployeeRepository;
use App\Repositories\Hr\DepartmentRepository;
use App\Repositories\Hr\DesignationRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HrService extends BaseService
{
    protected EmployeeRepository $employeeRepository;
    protected DepartmentRepository $departmentRepository;
    protected DesignationRepository $designationRepository;

    public function __construct(
        EmployeeRepository $employeeRepository,
        DepartmentRepository $departmentRepository,
        DesignationRepository $designationRepository
    ) {
        parent::__construct();
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
        $this->designationRepository = $designationRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->employeeRepository;
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['employee_no'])) {
                $data['employee_no'] = $this->generateEmployeeCode();
            }
            $data['status'] = $data['status'] ?? 'active';

            if (isset($data['email'])) {
                $existing = $this->employeeRepository->findByEmail($data['email']);
                if ($existing) {
                    throw new ServiceException("An employee with this email already exists.");
                }
            }

            $employee = $this->employeeRepository->create($data);

            $this->logActivity('employee_created', $employee);

            return $employee;
        });
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = $this->employeeRepository->getById($id);
            $employee = $this->employeeRepository->update($id, $data);

            $this->logActivity('employee_updated', $employee);

            return $employee;
        });
    }

    public function terminateEmployee(int $id, string $reason, ?\DateTime $terminationDate = null): Employee
    {
        return DB::transaction(function () use ($id, $reason, $terminationDate) {
            $employee = $this->employeeRepository->getById($id);

            if ($employee->status === 'terminated') {
                throw new ServiceException("Employee is already terminated.");
            }

            $employee = $this->employeeRepository->update($id, [
                'status' => 'terminated',
                'termination_date' => $terminationDate ?? now(),
                'termination_reason' => $reason,
            ]);

            $this->logActivity('employee_terminated', $employee);

            return $employee;
        });
    }

    public function processEvaluation(int $employeeId, array $evaluationData): array
    {
        return DB::transaction(function () use ($employeeId, $evaluationData) {
            $employee = $this->employeeRepository->getById($employeeId);

            $evaluation = [
                'employee_id' => $employeeId,
                'evaluator_id' => auth()->id(),
                'evaluation_date' => $evaluationData['evaluation_date'] ?? now(),
                'rating' => $evaluationData['rating'],
                'comments' => $evaluationData['comments'] ?? null,
                'areas_for_improvement' => $evaluationData['areas_for_improvement'] ?? null,
                'overall_score' => $evaluationData['overall_score'] ?? null,
            ];

            activity()->causedBy(auth()->user())->performedOn($employee)->withProperties($evaluation)->event('employee_evaluated')->log("HrService: Employee {$employeeId} evaluated with rating {$evaluationData['rating']}");

            return $evaluation;
        });
    }

    public function getStaffDirectory(?int $departmentId = null): Collection
    {
        if ($departmentId) {
            return $this->employeeRepository->getEmployeesByDepartmentWithUsers($departmentId);
        }
        return $this->employeeRepository->getActiveEmployees();
    }

    public function generateEmployeeCode(): string
    {
        $last = $this->employeeRepository->query()->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();

        $nextNumber = $last ? ((int) substr($last->employee_no, -5)) + 1 : 1;

        return 'EMP-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}

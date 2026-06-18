<?php

namespace App\Services\Attendance;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Attendance\LeaveRequest;
use App\Repositories\Attendance\LeaveRequestRepository;
use App\Repositories\Attendance\LeaveBalanceRepository;
use App\Repositories\Attendance\LeaveTypeRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class LeaveService extends BaseService
{
    protected LeaveRequestRepository $leaveRequestRepository;
    protected LeaveBalanceRepository $leaveBalanceRepository;
    protected LeaveTypeRepository $leaveTypeRepository;

    public function __construct(
        LeaveRequestRepository $leaveRequestRepository,
        LeaveBalanceRepository $leaveBalanceRepository,
        LeaveTypeRepository $leaveTypeRepository
    ) {
        $this->leaveRequestRepository = $leaveRequestRepository;
        $this->leaveBalanceRepository = $leaveBalanceRepository;
        $this->leaveTypeRepository = $leaveTypeRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->leaveRequestRepository;
    }

    public function applyLeave(array $data): LeaveRequest
    {
        return DB::transaction(function () use ($data) {
            $leaveType = $this->leaveTypeRepository->getById($data['leave_type_id']);

            if (isset($data['employee_id'])) {
                $balance = $this->leaveBalanceRepository->getAvailableBalance($data['employee_id'], $data['leave_type_id']);
                $days = $this->calculateLeaveDays($data['start_date'], $data['end_date']);

                if ($balance < $days) {
                    throw new ServiceException("Insufficient leave balance. Available: {$balance}, Required: {$days}");
                }
            }

            $data['status'] = 'pending';
            $data['applied_at'] = $data['applied_at'] ?? now();
            $data['total_days'] = $this->calculateLeaveDays($data['start_date'], $data['end_date']);

            $leave = $this->leaveRequestRepository->create($data);

            $this->logActivity('leave_applied', $leave);

            return $leave;
        });
    }

    public function approveLeave(int $id, ?string $approvedBy = null): LeaveRequest
    {
        return DB::transaction(function () use ($id, $approvedBy) {
            $leave = $this->leaveRequestRepository->getById($id);

            if ($leave->status !== 'pending') {
                throw new ServiceException("Leave request is already {$leave->status}.");
            }

            $this->leaveRequestRepository->approve($id, $approvedBy);

            if ($leave->employee_id) {
                $this->leaveBalanceRepository->deductLeave(
                    $leave->employee_id,
                    $leave->leave_type_id,
                    $leave->total_days
                );
            }

            $leave = $leave->fresh();

            $this->logActivity('leave_approved', $leave);

            return $leave;
        });
    }

    public function rejectLeave(int $id, string $reason): LeaveRequest
    {
        return DB::transaction(function () use ($id, $reason) {
            $leave = $this->leaveRequestRepository->getById($id);

            if ($leave->status !== 'pending') {
                throw new ServiceException("Leave request is already {$leave->status}.");
            }

            $this->leaveRequestRepository->reject($id, $reason);

            $leave = $leave->fresh();

            $this->logActivity('leave_rejected', $leave);

            return $leave;
        });
    }

    public function getLeaveBalance(int $employeeId, ?int $leaveTypeId = null): array
    {
        if ($leaveTypeId) {
            $balance = $this->leaveBalanceRepository->getAvailableBalance($employeeId, $leaveTypeId);
            $leaveType = $this->leaveTypeRepository->find($leaveTypeId);

            return [
                'employee_id' => $employeeId,
                'leave_type' => $leaveType->name,
                'balance' => $balance,
            ];
        }

        $balances = $this->leaveBalanceRepository->findByEmployee($employeeId);
        $result = ['employee_id' => $employeeId, 'balances' => []];

        foreach ($balances as $balance) {
            $leaveType = $this->leaveTypeRepository->find($balance->leave_type_id);
            $result['balances'][] = [
                'leave_type' => $leaveType->name,
                'total' => $balance->total_days,
                'used' => $balance->used,
                'balance' => $balance->balance,
            ];
        }

        return $result;
    }

    public function calculateLeaveEncashment(int $employeeId, int $leaveTypeId, float $days): array
    {
        $leaveType = $this->leaveTypeRepository->find($leaveTypeId);

        if (!$leaveType || !$leaveType->is_paid) {
            throw new ServiceException("Leave type is not eligible for encashment.");
        }

        $balance = $this->leaveBalanceRepository->getAvailableBalance($employeeId, $leaveTypeId);

        if ($days > $balance) {
            throw new ServiceException("Insufficient balance for encashment. Available: {$balance}");
        }

        return [
            'employee_id' => $employeeId,
            'leave_type' => $leaveType->name,
            'days' => $days,
            'rate_per_day' => $leaveType->encashment_rate ?? 0,
            'total_encashment' => $days * ($leaveType->encashment_rate ?? 0),
        ];
    }

    private function calculateLeaveDays(string $startDate, string $endDate): int
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        return $start->diffInDaysFiltered(function (\Carbon\Carbon $date) {
            return !$date->isWeekend();
        }, $end) + 1;
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreEmployeeRequest;
use App\Models\Hr\Employee;
use App\Services\Hr\HrService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponseTrait;

    protected HrService $hrService;

    public function __construct(HrService $hrService)
    {
        $this->hrService = $hrService;
    }

    public function index(Request $request): JsonResponse
    {
        $employees = Employee::with('department', 'designation', 'branch')->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))->when($request->designation_id, fn($q) => $q->where('designation_id', $request->designation_id))->when($request->employment_type, fn($q) => $q->where('employment_type', $request->employment_type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('employee_no', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%");
            }))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($employees, 'Employees retrieved');
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->hrService->createEmployee($request->validated());
        return $this->createdResponse($employee->load('department', 'designation'), 'Employee created');
    }

    public function show(int $id): JsonResponse
    {
        $employee = Employee::with('department', 'designation', 'branch', 'contracts', 'documents', 'evaluations')->findOrFail($id);
        return $this->successResponse($employee, 'Employee retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255|unique:employees,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'blood_group' => 'nullable|string|max:10',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'designation_id' => 'sometimes|integer|exists:designations,id',
            'qualification' => 'nullable|string|max:500',
            'present_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,inactive,terminated,resigned,suspended',
        ]);
        $employee->update($validated);
        return $this->updatedResponse($employee->fresh()->load('department', 'designation'), 'Employee updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Employee::findOrFail($id)->delete();
        return $this->deletedResponse('Employee deleted');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (!$query) return $this->errorResponse('Search query is required', 400);

        $employees = Employee::with('department', 'designation')->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('employee_no', 'like', "%{$query}%");
            })->limit(20)->get();

        return $this->successResponse($employees, 'Search results');
    }

    public function getByDepartment(int $departmentId): JsonResponse
    {
        $employees = Employee::with('designation')->where('department_id', $departmentId)->where('status', 'active')->orderBy('first_name')->get();
        return $this->successResponse($employees, 'Employees by department');
    }

    public function getByDesignation(int $designationId): JsonResponse
    {
        $employees = Employee::with('department')->where('designation_id', $designationId)->where('status', 'active')->orderBy('first_name')->get();
        return $this->successResponse($employees, 'Employees by designation');
    }

    public function getDirectory(Request $request): JsonResponse
    {
        $employees = Employee::with('department', 'designation')->where('status', 'active')->orderBy('first_name')->get()->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->full_name,
                'employee_no' => $e->employee_no,
                'department' => $e->department?->name,
                'designation' => $e->designation?->name,
                'phone' => $e->phone,
                'email' => $e->email,
                'photo' => $e->photo_url,
            ]);
        return $this->successResponse($employees, 'Employee directory');
    }
}

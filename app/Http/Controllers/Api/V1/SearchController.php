<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Student\Student;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use ApiResponseTrait;

    public function globalSearch(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (!$query || strlen($query) < 2) {
            return $this->errorResponse('Search query must be at least 2 characters', 400);
        }

        $results = [
            'students' => Student::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('admission_no', 'like', "%{$query}%");
            })->limit(5)->get(['id', 'first_name', 'last_name', 'admission_no']),

            'employees' => Employee::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('employee_no', 'like', "%{$query}%");
            })->limit(5)->get(['id', 'first_name', 'last_name', 'employee_no']),

            'users' => User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%");
            })->limit(5)->get(['id', 'name', 'email', 'user_type']),
        ];

        return $this->successResponse($results, 'Global search completed');
    }

    public function searchStudents(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $students = Student::with('class', 'section')->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('admission_no', 'like', "%{$query}%");
            })->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->limit(20)->get();

        return $this->successResponse($students, 'Student search results');
    }

    public function searchTeachers(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $teachers = Employee::with('department', 'designation')->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('employee_no', 'like', "%{$query}%");
            })->limit(20)->get();

        return $this->successResponse($teachers, 'Teacher search results');
    }

    public function searchEmployees(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $employees = Employee::with('department', 'designation')->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('employee_no', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%");
            })->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))->limit(20)->get();

        return $this->successResponse($employees, 'Employee search results');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('department', 'designation')->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('employee_no', 'like', "%{$request->search}%");
            }))->orderBy('created_at', 'desc')->paginate(20);

        $departments = Department::active()->orderBy('name')->get();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();
        return view('employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'employee_no' => 'required|string|max:50|unique:employees',
            'email' => 'required|email|max:255|unique:employees',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'blood_group' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'employment_type' => 'required|string|in:permanent,contract,temporary,probation,intern',
            'date_of_joining' => 'required|date',
            'qualification' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'work_shift' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
        ]);

        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    public function show(int $id)
    {
        $employee = Employee::with('department', 'designation', 'contracts', 'documents', 'evaluations')->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit(int $id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();
        return view('employees.edit', compact('employee', 'departments', 'designations'));
    }

    public function update(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255|unique:employees,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'designation_id' => 'sometimes|integer|exists:designations,id',
        ]);
        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(int $id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
}

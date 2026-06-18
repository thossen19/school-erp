<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('recruitment')
            ->leftJoin('departments', 'recruitment.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'recruitment.designation_id', '=', 'designations.id')
            ->select('recruitment.*', 'departments.name as department_name', 'designations.name as designation_name');

        if ($request->filled('status')) {
            $query->where('recruitment.status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('recruitment.job_title', 'like', "%{$request->search}%");
        }

        $postings = $query->orderBy('recruitment.created_at', 'desc')->paginate(15);

        return view('recruitment.index', compact('postings'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();
        return view('recruitment.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'vacancies' => 'required|integer|min:1|max:100',
            'salary_range' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'posted_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:posted_date',
            'status' => 'required|string|in:open,closed,draft',
        ]);

        DB::table('recruitment')->insert($validated + [
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('hr.recruitment')->with('success', 'Job posting created successfully');
    }

    public function show(int $id)
    {
        $posting = DB::table('recruitment')
            ->leftJoin('departments', 'recruitment.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'recruitment.designation_id', '=', 'designations.id')
            ->select('recruitment.*', 'departments.name as department_name', 'designations.name as designation_name')
            ->where('recruitment.id', $id)->firstOrFail();

        $applications = DB::table('job_applications')
            ->where('recruitment_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('recruitment.show', compact('posting', 'applications'));
    }

    public function edit(int $id)
    {
        $posting = DB::table('recruitment')->where('id', $id)->firstOrFail();
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();
        return view('recruitment.edit', compact('posting', 'departments', 'designations'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'job_title' => 'sometimes|string|max:255',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'designation_id' => 'sometimes|integer|exists:designations,id',
            'vacancies' => 'sometimes|integer|min:1|max:100',
            'salary_range' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'posted_date' => 'sometimes|date',
            'closing_date' => 'sometimes|date|after_or_equal:posted_date',
            'status' => 'sometimes|string|in:open,closed,draft',
        ]);

        DB::table('recruitment')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('hr.recruitment')->with('success', 'Job posting updated successfully');
    }

    public function destroy(int $id)
    {
        DB::table('recruitment')->where('id', $id)->delete();
        return redirect()->route('hr.recruitment')->with('success', 'Job posting deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Student\StudentHouse;
use App\Services\Student\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $students = Student::with('class', 'section', 'house')
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('admission_no', 'like', "%{$request->search}%")
                  ->orWhere('roll_number', 'like', "%{$request->search}%");
            }))
            ->when($request->date_from, fn($q) => $q->whereDate('date_of_birth', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('date_of_birth', '<=', $request->date_to))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $classes = ClassModel::active()->orderBy('name')->get();
        $sections = Section::where('class_id', $request->class_id)->get();

        $schoolId = session('school_id', auth()->user()->school_id ?? 1);
        return view('students.index', compact('students', 'classes', 'sections', 'schoolId'));
    }

    public function create()
    {
        $classes = ClassModel::active()->orderBy('name')->get();
        $houses = StudentHouse::active()->get();
        return view('students.create', compact('classes', 'houses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'admission_no' => 'nullable|string|max:50|unique:students,admission_no',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $validated['school_id'] = session('school_id', auth()->user()->school_id ?? 1);
        $this->studentService->createStudent($validated);
        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

    public function show(int $id)
    {
        $student = Student::with('class', 'section', 'house', 'parents', 'documents', 'awards', 'disciplines', 'medicalRecords')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit(int $id)
    {
        $student = Student::findOrFail($id);
        $classes = ClassModel::active()->orderBy('name')->get();
        $sections = Section::where('class_id', $student->class_id)->get();
        $houses = StudentHouse::active()->get();
        return view('students.edit', compact('student', 'classes', 'sections', 'houses'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|string|in:male,female,other',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email,' . $id,
        ]);

        $this->studentService->updateStudent($id, $validated);
        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }

    public function destroy(int $id)
    {
        Student::findOrFail($id)->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }

    public function portal()
    {
        $student = Student::with('class', 'section', 'house', 'parents', 'academicYear')
            ->where('user_id', auth()->id())
            ->first();

        return view('students.portal', compact('student'));
    }
}

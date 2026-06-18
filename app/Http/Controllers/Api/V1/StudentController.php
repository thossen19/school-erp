<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student\Student;
use App\Models\Student\StudentAward;
use App\Models\Student\StudentDiscipline;
use App\Models\Student\StudentDocument;
use App\Models\Student\StudentTimeline;
use App\Services\Student\StudentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    use ApiResponseTrait;

    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request): JsonResponse
    {
        $students = Student::with('class', 'section', 'house', 'parents')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->when($request->house_id, fn($q) => $q->where('house_id', $request->house_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('admission_no', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
            }))->when($request->gender, fn($q) => $q->where('gender', $request->gender))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($students, 'Students retrieved');
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }
        $student = $this->studentService->createStudent($data);
        return $this->createdResponse($student->load('class', 'section', 'house', 'parents'), 'Student created');
    }

    public function show(int $id): JsonResponse
    {
        $student = Student::with([
            'class', 'section', 'house', 'parents', 'documents', 'awards',
            'disciplineRecords', 'medicalRecords', 'academicHistories', 'promotions',
        ])->findOrFail($id);
        return $this->successResponse($student, 'Student retrieved');
    }

    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }
        $student = $this->studentService->updateStudent($id, $data);
        return $this->updatedResponse($student->load('class', 'section', 'house', 'parents'), 'Student updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Student::findOrFail($id)->delete();
        return $this->deletedResponse('Student deleted');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (!$query) {
            return $this->errorResponse('Search query is required', 400);
        }
        $students = Student::with('class', 'section')->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")->orWhere('last_name', 'like', "%{$query}%")->orWhere('admission_no', 'like', "%{$query}%")->orWhere('phone', 'like', "%{$query}%");
            })->limit(20)->get();
        return $this->successResponse($students, 'Search results');
    }

    public function getByClass(int $classId, Request $request): JsonResponse
    {
        $students = Student::with('section', 'house')->where('class_id', $classId)->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->orderBy('first_name')->get();
        return $this->successResponse($students, 'Students by class');
    }

    public function getBySection(int $classId, int $sectionId): JsonResponse
    {
        $students = Student::with('house')->where('class_id', $classId)->where('section_id', $sectionId)->orderBy('first_name')->get();
        return $this->successResponse($students, 'Students by section');
    }

    public function getByHouse(int $houseId): JsonResponse
    {
        $students = Student::with('class', 'section')->where('house_id', $houseId)->orderBy('first_name')->get();
        return $this->successResponse($students, 'Students by house');
    }

    public function promote(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'to_class_id' => 'required|integer|exists:classes,id',
            'to_section_id' => 'nullable|integer|exists:sections,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'remarks' => 'nullable|string|max:500',
        ]);

        $result = $this->studentService->promoteStudent($id, $request->to_class_id, $request->to_section_id, $request->only('academic_year_id', 'remarks'));
        return $this->successResponse($result, 'Student promoted');
    }

    public function transfer(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'to_school_id' => 'required|integer|exists:schools,id',
            'to_class_id' => 'required|integer|exists:classes,id',
            'to_section_id' => 'nullable|integer|exists:sections,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string|max:500',
            'tc_no' => 'nullable|string|max:50',
        ]);

        $result = $this->studentService->transferStudent($id, $request->all());
        return $this->successResponse($result, 'Student transferred');
    }

    public function addDocument(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($id);
        $path = $request->file('file')->store("students/{$id}/documents", 'public');

        $document = StudentDocument::create([
            'student_id' => $id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'description' => $request->description,
            'uploaded_by' => $request->user()->id,
        ]);

        return $this->createdResponse($document, 'Document added');
    }

    public function addDiscipline(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_type' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'action_taken' => 'nullable|string|max:2000',
            'severity' => 'required|string|in:minor,moderate,severe',
            'status' => 'nullable|string|in:open,resolved,dropped',
        ]);

        $record = StudentDiscipline::create(array_merge($request->validated(), [
            'student_id' => $id,
            'reported_by' => $request->user()->id,
        ]));

        return $this->createdResponse($record, 'Discipline record added');
    }

    public function addAward(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'award_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'date_awarded' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $award = StudentAward::create(array_merge($request->validated(), ['student_id' => $id]));

        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store("students/{$id}/awards", 'public');
            $award->update(['certificate_file' => $path]);
        }

        return $this->createdResponse($award, 'Award added');
    }

    public function getTimeline(int $id): JsonResponse
    {
        $timeline = StudentTimeline::where('student_id', $id)->orderBy('created_at', 'desc')->get();
        return $this->successResponse($timeline, 'Timeline retrieved');
    }

    public function getProfile(int $id): JsonResponse
    {
        $student = Student::with([
            'class', 'section', 'house', 'parents', 'documents', 'awards',
            'disciplineRecords', 'medicalRecords', 'academicHistories',
            'promotions', 'transfers', 'feeCollections', 'attendances',
        ])->findOrFail($id);

        return $this->successResponse($student, 'Student profile retrieved');
    }

    public function getStatistics(Request $request): JsonResponse
    {
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'by_gender' => Student::selectRaw('gender, count(*) as count')->groupBy('gender')->get(),
            'by_class' => Student::selectRaw('class_id, count(*) as count')->groupBy('class_id')->with('class:id,name')->get(),
            'by_house' => Student::selectRaw('house_id, count(*) as count')->groupBy('house_id')->with('house:id,name')->get(),
            'new_admissions_this_month' => Student::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_transferred' => Student::where('status', 'transferred')->count(),
            'total_graduated' => Student::where('status', 'alumni')->count(),
        ];

        return $this->successResponse($stats, 'Student statistics');
    }
}

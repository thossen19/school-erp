<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
    public function index()
    {
        $schoolId = 1;
        $stats = [
            'classes' => DB::table('classes')->where('school_id', $schoolId)->count(),
            'sections' => DB::table('sections')->where('school_id', $schoolId)->count(),
            'subjects' => DB::table('subjects')->where('school_id', $schoolId)->count(),
            'lessonPlans' => DB::table('lesson_plans')->count(),
            'assignments' => DB::table('assignments')->count(),
            'homework' => DB::table('homeworks')->count(),
        ];
        $recentLessonPlans = DB::table('lesson_plans')->where('lesson_plans.school_id', $schoolId)
            ->leftJoin('subjects', 'lesson_plans.subject_id', '=', 'subjects.id')
            ->select('lesson_plans.*', 'subjects.name as subject_name')
            ->orderBy('created_at', 'desc')->take(6)->get();
        return view('academics.index', compact('stats', 'recentLessonPlans'));
    }

    public function classes()
    {
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)
            ->orderBy('name')->paginate(15);
        return view('academics.classes', compact('classes'));
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:classes,code',
            'numeric_value' => 'nullable|integer',
            'education_level' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
        ]);
        $validated['school_id'] = 1;
        DB::table('classes')->insert($validated);
        return back()->with('success', 'Class created');
    }

    public function updateClass(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:classes,code,' . $id,
            'numeric_value' => 'nullable|integer',
            'education_level' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
        ]);
        DB::table('classes')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Class updated');
    }

    public function destroyClass(int $id)
    {
        DB::table('classes')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Class deleted');
    }

    public function sections()
    {
        $schoolId = 1;
        $sections = DB::table('sections')->where('sections.school_id', $schoolId)
            ->leftJoin('classes', 'sections.class_id', '=', 'classes.id')
            ->select('sections.*', 'classes.name as class_name')
            ->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get(['id', 'name', 'code']);
        return view('academics.sections', compact('sections', 'classes'));
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'class_id' => 'required|integer|exists:classes,id',
            'code' => 'nullable|string|max:50|unique:sections,code,NULL,id,class_id,' . $request->class_id,
        ]);
        $validated['school_id'] = 1;
        if (empty($validated['code'])) {
            $classCode = DB::table('classes')->where('id', $validated['class_id'])->value('code');
            $validated['code'] = ($classCode ?? 'CLS') . '-' . strtoupper($validated['name']);
        }
        DB::table('sections')->insert($validated);
        return back()->with('success', 'Section created');
    }

    public function updateSection(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'class_id' => 'required|integer|exists:classes,id',
            'code' => 'nullable|string|max:50|unique:sections,code,' . $id . ',id,class_id,' . $request->class_id,
        ]);
        if (empty($validated['code'])) {
            $classCode = DB::table('classes')->where('id', $validated['class_id'])->value('code');
            $validated['code'] = ($classCode ?? 'CLS') . '-' . strtoupper($validated['name']);
        }
        DB::table('sections')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Section updated');
    }

    public function destroySection(int $id)
    {
        DB::table('sections')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('academic.sections')->with('success', 'Section deleted');
    }

    public function subjects()
    {
        $schoolId = 1;
        $subjects = DB::table('subjects')->where('school_id', $schoolId)
            ->orderBy('name')->paginate(15);
        return view('academics.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:100', 'code' => 'required|string|max:50|unique:subjects,code', 'type' => 'nullable|string|max:20', 'max_marks' => 'nullable|numeric']);
        $validated['school_id'] = 1;
        DB::table('subjects')->insert($validated);
        return back()->with('success', 'Subject created');
    }

    public function updateSubject(Request $request, int $id)
    {
        $validated = $request->validate(['name' => 'required|string|max:100', 'code' => 'required|string|max:50|unique:subjects,code,' . $id, 'type' => 'nullable|string|max:20', 'max_marks' => 'nullable|numeric']);
        DB::table('subjects')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Subject updated');
    }

    public function destroySubject(int $id)
    {
        DB::table('subjects')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Subject deleted');
    }

    public function academicYears()
    {
        $schoolId = 1;
        $academicYears = DB::table('academic_years')->where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        return view('academics.academic-year', compact('academicYears'));
    }

    public function storeAcademicYear(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:100', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date', 'is_current' => 'nullable|boolean']);
        $validated['school_id'] = 1;
        if (!empty($validated['is_current'])) {
            DB::table('academic_years')->where('school_id', 1)->update(['is_current' => false]);
        }
        DB::table('academic_years')->insert($validated);
        return redirect()->route('academic.academic-years')->with('success', 'Academic year created');
    }

    public function destroyAcademicYear(int $id)
    {
        DB::table('academic_years')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('academic.academic-years')->with('success', 'Academic year deleted');
    }

    public function curriculum()
    {
        $schoolId = 1;
        $curricula = DB::table('class_subjects')
            ->where('class_subjects.school_id', $schoolId)
            ->leftJoin('classes', 'class_subjects.class_id', '=', 'classes.id')
            ->leftJoin('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('sections', 'class_subjects.section_id', '=', 'sections.id')
            ->select('class_subjects.*', 'classes.name as class_name', 'subjects.name as subject_name', 'subjects.code as subject_code', 'subjects.type as subject_type', 'sections.name as section_name')
            ->orderBy('classes.name')->orderBy('subjects.name')
            ->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('academics.curriculum', compact('curricula', 'classes', 'subjects', 'sections'));
    }

    public function storeCurriculum(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'is_compulsory' => 'nullable|boolean',
            'max_periods_per_week' => 'nullable|integer|min:1|max:50',
            'total_marks' => 'nullable|numeric|min:1',
        ]);
        $validated['school_id'] = 1;
        $validated['is_compulsory'] = $request->boolean('is_compulsory');
        $validated['max_periods_per_week'] = $validated['max_periods_per_week'] ?? 5;
        $validated['total_marks'] = $validated['total_marks'] ?? 100;

        $exists = DB::table('class_subjects')
            ->where('school_id', 1)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('academic.curriculum')->with('error', 'Subject already assigned to this class & section.');
        }

        DB::table('class_subjects')->insert($validated);
        return redirect()->route('academic.curriculum')->with('success', 'Curriculum entry created');
    }

    public function updateCurriculum(Request $request, int $id)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'is_compulsory' => 'nullable|boolean',
            'max_periods_per_week' => 'nullable|integer|min:1|max:50',
            'total_marks' => 'nullable|numeric|min:1',
        ]);
        $validated['is_compulsory'] = $request->boolean('is_compulsory');
        $validated['max_periods_per_week'] = $validated['max_periods_per_week'] ?? 5;
        $validated['total_marks'] = $validated['total_marks'] ?? 100;

        $duplicate = DB::table('class_subjects')
            ->where('school_id', 1)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return redirect()->route('academic.curriculum')->with('error', 'Another entry already exists for this class, section & subject.');
        }

        DB::table('class_subjects')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('academic.curriculum')->with('success', 'Curriculum entry updated');
    }

    public function destroyCurriculum(int $id)
    {
        DB::table('class_subjects')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('academic.curriculum')->with('success', 'Curriculum entry deleted');
    }

    public function lessonPlans(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('lesson_plans')->where('lesson_plans.school_id', $schoolId)
            ->leftJoin('subjects', 'lesson_plans.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'lesson_plans.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'lesson_plans.section_id', '=', 'sections.id')
            ->leftJoin('users', 'lesson_plans.teacher_id', '=', 'users.id')
            ->select('lesson_plans.*', 'subjects.name as subject_name', 'classes.name as class_name', 'sections.name as section_name', 'users.name as teacher_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lesson_plans.title', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('classes.name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($classId = $request->input('class_id')) {
            $query->where('lesson_plans.class_id', $classId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('lesson_plans.subject_id', $subjectId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('lesson_plans.teacher_id', $teacherId);
        }
        if ($status = $request->input('status')) {
            $query->where('lesson_plans.status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('lesson_plans.lesson_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('lesson_plans.lesson_date', '<=', $dateTo);
        }

        $lessonPlans = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('users')->orderBy('name')->get(['id', 'name']);
        return view('academics.lesson-plans', compact('lessonPlans', 'subjects', 'classes', 'sections', 'teachers'));
    }

    public function storeLessonPlan(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'materials' => 'nullable|string',
            'activities' => 'nullable|string',
            'assessment_method' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50',
            'lesson_content' => 'nullable|string',
            'lesson_date' => 'nullable|date',
        ]);
        $validated['school_id'] = 1;
        if (empty($validated['status'])) $validated['status'] = 'draft';
        if (empty($validated['duration'])) $validated['duration'] = null;
        DB::table('lesson_plans')->insert($validated);
        return back()->with('success', 'Lesson plan created');
    }

    public function updateLessonPlan(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'materials' => 'nullable|string',
            'activities' => 'nullable|string',
            'assessment_method' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:50',
            'lesson_content' => 'nullable|string',
            'lesson_date' => 'nullable|date',
        ]);
        if (empty($validated['status'])) $validated['status'] = 'draft';
        DB::table('lesson_plans')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Lesson plan updated');
    }

    public function destroyLessonPlan(int $id)
    {
        DB::table('lesson_plans')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Lesson plan deleted');
    }

    public function assignments(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('assignments')->where('assignments.school_id', $schoolId)
            ->leftJoin('subjects', 'assignments.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'assignments.class_id', '=', 'classes.id')
            ->leftJoin('users', 'assignments.teacher_id', '=', 'users.id')
            ->select('assignments.*', 'subjects.name as subject_name', 'classes.name as class_name', 'users.name as teacher_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('assignments.title', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('classes.name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($classId = $request->input('class_id')) {
            $query->where('assignments.class_id', $classId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('assignments.subject_id', $subjectId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('assignments.teacher_id', $teacherId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('assignments.due_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('assignments.due_date', '<=', $dateTo);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('users')->orderBy('name')->get(['id', 'name']);
        return view('academics.assignments', compact('assignments', 'subjects', 'classes', 'sections', 'teachers'));
    }

    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_marks' => 'nullable|numeric',
        ]);
        $validated['school_id'] = 1;
        DB::table('assignments')->insert($validated);
        return back()->with('success', 'Assignment created');
    }

    public function updateAssignment(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_marks' => 'nullable|numeric',
        ]);
        DB::table('assignments')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Assignment updated');
    }

    public function destroyAssignment(int $id)
    {
        DB::table('assignments')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Assignment deleted');
    }

    public function homework(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('homeworks')->where('homeworks.school_id', $schoolId)
            ->leftJoin('subjects', 'homeworks.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'homeworks.class_id', '=', 'classes.id')
            ->leftJoin('users', 'homeworks.teacher_id', '=', 'users.id')
            ->select('homeworks.*', 'subjects.name as subject_name', 'classes.name as class_name', 'users.name as teacher_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('homeworks.title', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('classes.name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($classId = $request->input('class_id')) {
            $query->where('homeworks.class_id', $classId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('homeworks.subject_id', $subjectId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('homeworks.teacher_id', $teacherId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('homeworks.due_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('homeworks.due_date', '<=', $dateTo);
        }

        $homeworks = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('users')->orderBy('name')->get(['id', 'name']);
        return view('academics.homework', compact('homeworks', 'subjects', 'classes', 'sections', 'teachers'));
    }

    public function storeHomework(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        $validated['school_id'] = 1;
        DB::table('homeworks')->insert($validated);
        return back()->with('success', 'Homework created');
    }

    public function updateHomework(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        DB::table('homeworks')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Homework updated');
    }

    public function destroyHomework(int $id)
    {
        DB::table('homeworks')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Homework deleted');
    }

    public function studyMaterials(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('study_materials')->where('study_materials.school_id', $schoolId)
            ->leftJoin('subjects', 'study_materials.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'study_materials.class_id', '=', 'classes.id')
            ->leftJoin('users', 'study_materials.teacher_id', '=', 'users.id')
            ->select('study_materials.*', 'subjects.name as subject_name', 'classes.name as class_name', 'users.name as teacher_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('study_materials.title', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('classes.name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($classId = $request->input('class_id')) {
            $query->where('study_materials.class_id', $classId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('study_materials.subject_id', $subjectId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('study_materials.teacher_id', $teacherId);
        }
        if ($type = $request->input('type')) {
            $query->where('study_materials.type', $type);
        }

        $materials = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('users')->orderBy('name')->get(['id', 'name']);
        return view('academics.study-materials', compact('materials', 'subjects', 'classes', 'sections', 'teachers'));
    }

    public function storeStudyMaterial(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
        ]);
        $validated['school_id'] = 1;
        DB::table('study_materials')->insert($validated);
        return back()->with('success', 'Study material created');
    }

    public function updateStudyMaterial(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
        ]);
        DB::table('study_materials')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Study material updated');
    }

    public function destroyStudyMaterial(int $id)
    {
        DB::table('study_materials')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Study material deleted');
    }

    public function teacherDiary(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('teacher_diaries')->where('teacher_diaries.school_id', $schoolId)
            ->leftJoin('users', 'teacher_diaries.teacher_id', '=', 'users.id')
            ->leftJoin('subjects', 'teacher_diaries.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'teacher_diaries.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'teacher_diaries.section_id', '=', 'sections.id')
            ->select('teacher_diaries.*', 'users.name as teacher_name', 'subjects.name as subject_name', 'classes.name as class_name', 'sections.name as section_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('teacher_diaries.topic', 'like', "%{$search}%")
                  ->orWhere('teacher_diaries.lesson_taught', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('classes.name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        if ($classId = $request->input('class_id')) {
            $query->where('teacher_diaries.class_id', $classId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('teacher_diaries.subject_id', $subjectId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('teacher_diaries.teacher_id', $teacherId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('teacher_diaries.date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('teacher_diaries.date', '<=', $dateTo);
        }

        $diaries = $query->orderBy('teacher_diaries.date', 'desc')->paginate(15)->appends($request->query());
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('users')->orderBy('name')->get(['id', 'name']);
        return view('academics.teacher-diary', compact('diaries', 'subjects', 'classes', 'sections', 'teachers'));
    }

    public function storeDiary(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'date' => 'required|date',
            'topic' => 'nullable|string|max:125',
            'lesson_taught' => 'nullable|string',
            'student_participation' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $validated['school_id'] = 1;
        DB::table('teacher_diaries')->insert($validated);
        return back()->with('success', 'Diary entry created');
    }

    public function updateDiary(Request $request, int $id)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'required|integer|exists:users,id',
            'date' => 'required|date',
            'topic' => 'nullable|string|max:125',
            'lesson_taught' => 'nullable|string',
            'student_participation' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        DB::table('teacher_diaries')->where('id', $id)->where('school_id', 1)->update($validated);
        return back()->with('success', 'Diary entry updated');
    }

    public function destroyDiary(int $id)
    {
        DB::table('teacher_diaries')->where('id', $id)->where('school_id', 1)->delete();
        return back()->with('success', 'Diary entry deleted');
    }

    public function reports()
    {
        $schoolId = 1;
        $totalClasses = DB::table('classes')->where('school_id', $schoolId)->count();
        $totalSections = DB::table('sections')->where('school_id', $schoolId)->count();
        $totalSubjects = DB::table('subjects')->where('school_id', $schoolId)->count();
        $totalStudents = DB::table('students')->where('school_id', $schoolId)->count();
        $totalLessonPlans = DB::table('lesson_plans')->where('school_id', $schoolId)->count();
        $totalAssignments = DB::table('assignments')->where('school_id', $schoolId)->count();
        $totalHomework = DB::table('homeworks')->where('school_id', $schoolId)->count();
        $totalMaterials = DB::table('study_materials')->where('school_id', $schoolId)->count();
        $totalDiaryEntries = DB::table('teacher_diaries')->where('school_id', $schoolId)->count();

        $classStudentCounts = DB::table('classes')->where('classes.school_id', $schoolId)
            ->leftJoin('students', 'classes.id', '=', 'students.class_id')
            ->select('classes.name', DB::raw('count(students.id) as student_count'))
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.name')->get();

        $lessonPlanStatus = DB::table('lesson_plans')->where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();

        return view('academics.reports', compact(
            'totalClasses', 'totalSections', 'totalSubjects', 'totalStudents',
            'totalLessonPlans', 'totalAssignments', 'totalHomework', 'totalMaterials',
            'totalDiaryEntries', 'classStudentCounts', 'lessonPlanStatus'
        ));
    }
}

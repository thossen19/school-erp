<?php

namespace App\Services\Academic;

use App\Contracts\RepositoryInterface;
use App\Models\Academic\ClassModel;
use App\Repositories\Academic\ClassRepository;
use App\Repositories\Academic\SectionRepository;
use App\Repositories\Academic\SubjectRepository;
use App\Repositories\Academic\ClassSubjectRepository;
use App\Repositories\Academic\LessonPlanRepository;
use App\Repositories\Academic\AssignmentRepository;
use App\Repositories\Academic\HomeworkRepository;
use App\Repositories\Academic\StudyMaterialRepository;
use App\Repositories\Hr\EmployeeRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AcademicService extends BaseService
{
    protected ClassRepository $classRepository;
    protected SectionRepository $sectionRepository;
    protected SubjectRepository $subjectRepository;
    protected ClassSubjectRepository $classSubjectRepository;
    protected LessonPlanRepository $lessonPlanRepository;
    protected AssignmentRepository $assignmentRepository;
    protected HomeworkRepository $homeworkRepository;
    protected StudyMaterialRepository $studyMaterialRepository;
    protected EmployeeRepository $employeeRepository;

    public function __construct(
        ClassRepository $classRepository,
        SectionRepository $sectionRepository,
        SubjectRepository $subjectRepository,
        ClassSubjectRepository $classSubjectRepository,
        LessonPlanRepository $lessonPlanRepository,
        AssignmentRepository $assignmentRepository,
        HomeworkRepository $homeworkRepository,
        StudyMaterialRepository $studyMaterialRepository,
        EmployeeRepository $employeeRepository
    ) {
        parent::__construct();
        $this->classRepository = $classRepository;
        $this->sectionRepository = $sectionRepository;
        $this->subjectRepository = $subjectRepository;
        $this->classSubjectRepository = $classSubjectRepository;
        $this->lessonPlanRepository = $lessonPlanRepository;
        $this->assignmentRepository = $assignmentRepository;
        $this->homeworkRepository = $homeworkRepository;
        $this->studyMaterialRepository = $studyMaterialRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->classRepository;
    }

    public function createClass(array $data): ClassModel
    {
        return DB::transaction(function () use ($data) {
            $class = $this->classRepository->create($data);
            $this->logActivity('class_created', $class);
            return $class;
        });
    }

    public function createSection(array $data): \App\Models\Academic\Section
    {
        return DB::transaction(function () use ($data) {
            $section = $this->sectionRepository->create($data);
            $this->logActivity('section_created', $section);
            return $section;
        });
    }

    public function createSubject(array $data): \App\Models\Academic\Subject
    {
        return DB::transaction(function () use ($data) {
            $subject = $this->subjectRepository->create($data);
            $this->logActivity('subject_created', $subject);
            return $subject;
        });
    }

    public function assignSubject(int $classId, int $subjectId, array $data = []): \App\Models\Academic\ClassSubject
    {
        return DB::transaction(function () use ($classId, $subjectId, $data) {
            $classSubject = $this->classSubjectRepository->assignSubjectToClass($classId, $subjectId, $data);
            $this->logActivity('subject_assigned_to_class', $classSubject);
            return $classSubject;
        });
    }

    public function assignTeacher(int $classId, int $subjectId, int $employeeId): \App\Models\Academic\ClassSubject
    {
        return DB::transaction(function () use ($classId, $subjectId, $employeeId) {
            $employee = $this->employeeRepository->getById($employeeId);

            $classSubject = $this->classSubjectRepository->findByClassAndSubject($classId, $subjectId);

            if ($classSubject) {
                $classSubject = $this->classSubjectRepository->update($classSubject->id, ['employee_id' => $employeeId]);
            } else {
                $classSubject = $this->classSubjectRepository->assignSubjectToClass($classId, $subjectId, [
                    'employee_id' => $employeeId,
                ]);
            }

            $this->logActivity('teacher_assigned_to_subject', $classSubject);

            return $classSubject;
        });
    }

    public function createLessonPlan(array $data): \App\Models\Academic\LessonPlan
    {
        return DB::transaction(function () use ($data) {
            $lessonPlan = $this->lessonPlanRepository->create($data);
            $this->logActivity('lesson_plan_created', $lessonPlan);
            return $lessonPlan;
        });
    }

    public function createAssignment(array $data): \App\Models\Academic\Assignment
    {
        return DB::transaction(function () use ($data) {
            $assignment = $this->assignmentRepository->create($data);
            $this->logActivity('assignment_created', $assignment);
            return $assignment;
        });
    }

    public function createHomework(array $data): \App\Models\Academic\Homework
    {
        return DB::transaction(function () use ($data) {
            $homework = $this->homeworkRepository->create($data);
            $this->logActivity('homework_created', $homework);
            return $homework;
        });
    }

    public function uploadStudyMaterial(array $data): \App\Models\Academic\StudyMaterial
    {
        return DB::transaction(function () use ($data) {
            $material = $this->studyMaterialRepository->create($data);
            $this->logActivity('study_material_uploaded', $material);
            return $material;
        });
    }
}

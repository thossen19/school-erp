<?php

namespace App\Services\Student;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Student\Student;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentDocumentRepository;
use App\Repositories\Student\StudentDisciplineRepository;
use App\Repositories\Student\StudentAwardRepository;
use App\Repositories\Student\StudentPromotionRepository;
use App\Repositories\Student\StudentTransferRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentService extends BaseService
{
    protected StudentRepository $studentRepository;
    protected StudentDocumentRepository $documentRepository;
    protected StudentDisciplineRepository $disciplineRepository;
    protected StudentAwardRepository $awardRepository;
    protected StudentPromotionRepository $promotionRepository;
    protected StudentTransferRepository $transferRepository;

    public function __construct(
        StudentRepository $studentRepository,
        StudentDocumentRepository $documentRepository,
        StudentDisciplineRepository $disciplineRepository,
        StudentAwardRepository $awardRepository,
        StudentPromotionRepository $promotionRepository,
        StudentTransferRepository $transferRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->documentRepository = $documentRepository;
        $this->disciplineRepository = $disciplineRepository;
        $this->awardRepository = $awardRepository;
        $this->promotionRepository = $promotionRepository;
        $this->transferRepository = $transferRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->studentRepository;
    }

    public function createStudent(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['admission_no'])) {
                $data['admission_no'] = $this->generateStudentCode();
            }
            $data['status'] = $data['status'] ?? 'active';

            $student = $this->studentRepository->create($data);

            if (isset($data['parents']) && is_array($data['parents'])) {
                $student->parents()->attach($data['parents']);
            }

            $this->logActivity('student_created', $student);

            return $student;
        });
    }

    public function updateStudent(int $id, array $data): Student
    {
        return DB::transaction(function () use ($id, $data) {
            $student = $this->studentRepository->getById($id);
            $student = $this->studentRepository->update($id, $data);

            if (isset($data['parents']) && is_array($data['parents'])) {
                $student->parents()->sync($data['parents']);
            }

            $this->logActivity('student_updated', $student);

            return $student;
        });
    }

    public function promoteStudent(int $studentId, int $toClassId, ?int $toSectionId = null, array $data = []): Model
    {
        return DB::transaction(function () use ($studentId, $toClassId, $toSectionId, $data) {
            $student = $this->studentRepository->getById($studentId);

            $promotion = $this->promotionRepository->create([
                'student_id' => $studentId,
                'from_class_id' => $student->class_id,
                'to_class_id' => $toClassId,
                'from_section_id' => $student->section_id,
                'to_section_id' => $toSectionId,
                'academic_year_id' => $data['academic_year_id'] ?? null,
                'status' => 'promoted',
                'promotion_date' => $data['promotion_date'] ?? now(),
                'remarks' => $data['remarks'] ?? null,
            ]);

            $updateData = ['class_id' => $toClassId];
            if ($toSectionId) {
                $updateData['section_id'] = $toSectionId;
            }
            $this->studentRepository->update($studentId, $updateData);

            $this->logActivity('student_promoted', $promotion);

            return $promotion;
        });
    }

    public function transferStudent(int $studentId, array $transferData): Model
    {
        return DB::transaction(function () use ($studentId, $transferData) {
            $student = $this->studentRepository->getById($studentId);

            $transfer = $this->transferRepository->transfer($studentId, array_merge($transferData, [
                'from_school_id' => $transferData['from_school_id'] ?? null,
                'transfer_date' => $transferData['transfer_date'] ?? now(),
                'status' => 'pending',
            ]));

            $this->studentRepository->update($studentId, ['status' => 'transferred']);

            $this->logActivity('student_transferred', $transfer);

            return $transfer;
        });
    }

    public function addDocument(int $studentId, array $documentData): Model
    {
        return DB::transaction(function () use ($studentId, $documentData) {
            $student = $this->studentRepository->getById($studentId);

            $documentData['student_id'] = $studentId;
            $document = $this->documentRepository->create($documentData);

            $this->logActivity('student_document_added', $document);

            return $document;
        });
    }

    public function addDisciplineRecord(int $studentId, array $data): Model
    {
        return DB::transaction(function () use ($studentId, $data) {
            $student = $this->studentRepository->getById($studentId);

            $data['student_id'] = $studentId;
            $data['incident_date'] = $data['incident_date'] ?? now();
            $data['status'] = $data['status'] ?? 'pending';

            $record = $this->disciplineRepository->create($data);

            $this->logActivity('discipline_record_added', $record);

            return $record;
        });
    }

    public function addAward(int $studentId, array $awardData): Model
    {
        return DB::transaction(function () use ($studentId, $awardData) {
            $student = $this->studentRepository->getById($studentId);

            $awardData['student_id'] = $studentId;
            $awardData['award_date'] = $awardData['award_date'] ?? now();

            $award = $this->awardRepository->create($awardData);

            $this->logActivity('student_award_added', $award);

            return $award;
        });
    }

    public function generateStudentCode(): string
    {
        $last = Student::whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();

        $nextNumber = $last ? ((int) substr($last->admission_no, -5)) + 1 : 1;

        return 'STU-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function getStudentProfile(int $id): ?Student
    {
        $student = $this->studentRepository->with([
            'class', 'section', 'parents', 'medicalRecord',
            'documents', 'awards', 'attendance', 'feeCollections',
        ])->find($id);

        $this->logActivity('student_profile_viewed', $student);

        return $student;
    }

    public function searchStudents(string $keyword): Collection
    {
        return $this->studentRepository->searchStudents($keyword);
    }

    public function getStudentTimeline(int $studentId): Collection
    {
        $student = $this->studentRepository->getById($studentId);

        $promotions = $this->promotionRepository->findByStudent($studentId);
        $transfers = $this->transferRepository->findByStudent($studentId);
        $disciplines = $this->disciplineRepository->findByStudent($studentId);
        $awards = $this->awardRepository->findByStudent($studentId);
        $documents = $this->documentRepository->findByStudent($studentId);

        $timeline = collect();

        foreach ($promotions as $item) {
            $timeline->push(['type' => 'promotion', 'date' => $item->promotion_date, 'data' => $item]);
        }
        foreach ($transfers as $item) {
            $timeline->push(['type' => 'transfer', 'date' => $item->transfer_date, 'data' => $item]);
        }
        foreach ($disciplines as $item) {
            $timeline->push(['type' => 'discipline', 'date' => $item->incident_date, 'data' => $item]);
        }
        foreach ($awards as $item) {
            $timeline->push(['type' => 'award', 'date' => $item->award_date, 'data' => $item]);
        }
        foreach ($documents as $item) {
            $timeline->push(['type' => 'document', 'date' => $item->created_at, 'data' => $item]);
        }

        return $timeline->sortByDesc('date')->values();
    }
}

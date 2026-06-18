<?php

namespace App\Services\Student;

use App\Contracts\RepositoryInterface;
use App\Models\Student\StudentMedicalRecord;
use App\Repositories\Student\StudentMedicalRecordRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentMedicalService extends BaseService
{
    protected StudentMedicalRecordRepository $medicalRepository;

    public function __construct(StudentMedicalRecordRepository $medicalRepository)
    {
        parent::__construct();
        $this->medicalRepository = $medicalRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->medicalRepository;
    }

    public function updateMedicalRecord(int $studentId, array $data): StudentMedicalRecord
    {
        return DB::transaction(function () use ($studentId, $data) {
            $existing = $this->medicalRepository->findByStudent($studentId)->first();

            if ($existing) {
                $record = $this->medicalRepository->update($existing->id, array_merge($data, ['student_id' => $studentId]));
            } else {
                $data['student_id'] = $studentId;
                $record = $this->medicalRepository->create($data);
            }

            $this->logActivity('medical_record_updated', $record);

            return $record;
        });
    }

    public function addVaccination(int $studentId, array $vaccinationData): StudentMedicalRecord
    {
        return DB::transaction(function () use ($studentId, $vaccinationData) {
            $existing = $this->medicalRepository->findByStudent($studentId)->first();

            $vaccinations = [];
            if ($existing && $existing->vaccinations) {
                $vaccinations = is_string($existing->vaccinations)
                    ? json_decode($existing->vaccinations, true)
                    : ($existing->vaccinations ?? []);
            }

            $vaccinations[] = $vaccinationData;

            if ($existing) {
                $record = $this->medicalRepository->update($existing->id, [
                    'vaccinations' => json_encode($vaccinations),
                    'immunization_status' => 'updated',
                ]);
            } else {
                $record = $this->medicalRepository->create([
                    'student_id' => $studentId,
                    'vaccinations' => json_encode($vaccinations),
                    'immunization_status' => 'updated',
                ]);
            }

            $this->logActivity('vaccination_added', $record);

            return $record;
        });
    }

    public function getHealthReport(int $studentId): array
    {
        $records = $this->medicalRepository->findByStudent($studentId);

        $report = [
            'student_id' => $studentId,
            'records' => $records,
            'has_allergies' => $records->filter(fn($r) => !empty($r->allergies))->isNotEmpty(),
            'has_conditions' => $records->filter(fn($r) => !empty($r->medical_conditions))->isNotEmpty(),
            'on_medication' => $records->filter(fn($r) => !empty($r->current_medication))->isNotEmpty(),
            'immunization_status' => $records->first()->immunization_status ?? 'unknown',
        ];

        $this->logActivity('health_report_viewed', ['student_id' => $studentId]);

        return $report;
    }
}

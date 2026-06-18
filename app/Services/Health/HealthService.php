<?php

namespace App\Services\Health;

use App\Contracts\RepositoryInterface;
use App\Models\Health\HealthRecord;
use App\Repositories\Health\HealthRecordRepository;
use App\Repositories\Health\VaccinationRecordRepository;
use App\Repositories\Health\MedicineRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class HealthService extends BaseService
{
    protected HealthRecordRepository $healthRecordRepository;
    protected VaccinationRecordRepository $vaccinationRepository;
    protected MedicineRepository $medicineRepository;

    public function __construct(
        HealthRecordRepository $healthRecordRepository,
        VaccinationRecordRepository $vaccinationRepository,
        MedicineRepository $medicineRepository
    ) {
        parent::__construct();
        $this->healthRecordRepository = $healthRecordRepository;
        $this->vaccinationRepository = $vaccinationRepository;
        $this->medicineRepository = $medicineRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->healthRecordRepository;
    }

    public function createHealthRecord(array $data): HealthRecord
    {
        return DB::transaction(function () use ($data) {
            $data['checkup_date'] = $data['checkup_date'] ?? now();
            $record = $this->healthRecordRepository->create($data);

            $this->logActivity('health_record_created', $record);

            return $record;
        });
    }

    public function scheduleCheckup(array $data): HealthRecord
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'scheduled';
            $record = $this->healthRecordRepository->create($data);

            $this->logActivity('checkup_scheduled', $record);

            return $record;
        });
    }

    public function trackVaccination(array $data): \App\Models\Health\VaccinationRecord
    {
        return DB::transaction(function () use ($data) {
            $vaccination = $this->vaccinationRepository->create($data);

            $this->logActivity('vaccination_tracked', $vaccination);

            return $vaccination;
        });
    }

    public function trackMedicine(array $data): \App\Models\Health\Medicine
    {
        return DB::transaction(function () use ($data) {
            $medicine = $this->medicineRepository->create($data);

            $this->logActivity('medicine_tracked', $medicine);

            return $medicine;
        });
    }

    public function getHealthReport(int $studentId): array
    {
        $records = $this->healthRecordRepository->findByStudent($studentId);
        $vaccinations = $this->vaccinationRepository->findByStudent($studentId);
        $latest = $this->healthRecordRepository->getLatestByStudent($studentId);

        $report = [
            'student_id' => $studentId,
            'latest_checkup' => $latest,
            'total_checkups' => $records->count(),
            'vaccinations' => $vaccinations,
            'completed_vaccinations' => $vaccinations->where('status', 'completed')->count(),
            'pending_vaccinations' => $vaccinations->where('status', 'pending')->count(),
            'records' => $records,
        ];

        $this->logActivity('health_report_viewed', ['student_id' => $studentId]);

        return $report;
    }
}

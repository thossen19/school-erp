<?php

namespace App\Services\Asset;

use App\Contracts\RepositoryInterface;
use App\Models\Asset\AssetMaintenance;
use App\Repositories\Asset\AssetMaintenanceRepository;
use App\Repositories\Asset\AssetRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceService extends BaseService
{
    protected AssetMaintenanceRepository $maintenanceRepository;
    protected AssetRepository $assetRepository;

    public function __construct(
        AssetMaintenanceRepository $maintenanceRepository,
        AssetRepository $assetRepository
    ) {
        parent::__construct();
        $this->maintenanceRepository = $maintenanceRepository;
        $this->assetRepository = $assetRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->maintenanceRepository;
    }

    public function scheduleMaintenance(array $data): AssetMaintenance
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'scheduled';

            $maintenance = $this->maintenanceRepository->scheduleMaintenance($data);

            if (isset($data['asset_id'])) {
                $this->assetRepository->update($data['asset_id'], ['status' => 'maintenance']);
            }

            $this->logActivity('maintenance_scheduled', $maintenance);

            return $maintenance;
        });
    }

    public function recordMaintenance(array $data): AssetMaintenance
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'completed';
            $data['completed_at'] = $data['completed_at'] ?? now();

            $maintenance = $this->maintenanceRepository->create($data);

            if (isset($data['asset_id'])) {
                $this->assetRepository->update($data['asset_id'], ['status' => 'active']);
            }

            $this->logActivity('maintenance_recorded', $maintenance);

            return $maintenance;
        });
    }

    public function trackMaintenanceCost(int $assetId): array
    {
        $records = $this->maintenanceRepository->findByAsset($assetId);
        $totalCost = $this->maintenanceRepository->getTotalMaintenanceCost($assetId);

        $report = [
            'asset_id' => $assetId,
            'total_maintenance_count' => $records->count(),
            'total_cost' => $totalCost,
            'average_cost_per_maintenance' => $records->count() > 0
                ? round($totalCost / $records->count(), 2)
                : 0,
            'records' => $records,
        ];

        $this->logActivity('maintenance_cost_tracked', $report);

        return $report;
    }
}

<?php

namespace App\Services\Asset;

use App\Contracts\RepositoryInterface;
use App\Models\Asset\Asset;
use App\Repositories\Asset\AssetRepository;
use App\Repositories\Asset\AssetAllocationRepository;
use App\Repositories\Asset\AssetMaintenanceRepository;
use App\Repositories\Asset\AssetDepreciationRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AssetService extends BaseService
{
    protected AssetRepository $assetRepository;
    protected AssetAllocationRepository $allocationRepository;
    protected AssetMaintenanceRepository $maintenanceRepository;
    protected AssetDepreciationRepository $depreciationRepository;

    public function __construct(
        AssetRepository $assetRepository,
        AssetAllocationRepository $allocationRepository,
        AssetMaintenanceRepository $maintenanceRepository,
        AssetDepreciationRepository $depreciationRepository
    ) {
        parent::__construct();
        $this->assetRepository = $assetRepository;
        $this->allocationRepository = $allocationRepository;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->depreciationRepository = $depreciationRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->assetRepository;
    }

    public function registerAsset(array $data): Asset
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['asset_code'])) {
                $data['asset_code'] = 'AST-' . now()->format('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            $data['status'] = $data['status'] ?? 'active';

            $asset = $this->assetRepository->create($data);

            if (isset($data['initial_depreciation'])) {
                $this->depreciationRepository->recordDepreciation([
                    'asset_id' => $asset->id,
                    'depreciation_method' => $data['depreciation_method'] ?? 'straight_line',
                    'depreciation_amount' => $data['initial_depreciation'],
                    'year' => now()->year,
                    'period' => 1,
                ]);
            }

            $this->logActivity('asset_registered', $asset);

            return $asset;
        });
    }

    public function allocateAsset(int $assetId, int $employeeId, array $data = []): \App\Models\Asset\AssetAllocation
    {
        return DB::transaction(function () use ($assetId, $employeeId, $data) {
            $asset = $this->assetRepository->getById($assetId);

            if ($asset->status !== 'active') {
                throw new \App\Exceptions\ServiceException("Asset is not available for allocation.");
            }

            $currentAllocation = $this->allocationRepository->getCurrentAllocation($assetId);
            if ($currentAllocation) {
                throw new \App\Exceptions\ServiceException("Asset is already allocated.");
            }

            $allocation = $this->allocationRepository->allocate($assetId, $employeeId, $data);

            $this->assetRepository->update($assetId, ['status' => 'allocated']);

            $this->logActivity('asset_allocated', $allocation);

            return $allocation;
        });
    }

    public function maintainAsset(array $data): \App\Models\Asset\AssetMaintenance
    {
        return DB::transaction(function () use ($data) {
            $maintenance = $this->maintenanceRepository->scheduleMaintenance($data);

            if (isset($data['asset_id'])) {
                $this->assetRepository->update($data['asset_id'], ['status' => 'maintenance']);
            }

            $this->logActivity('asset_maintenance_recorded', $maintenance);

            return $maintenance;
        });
    }

    public function calculateDepreciation(int $assetId): array
    {
        return DB::transaction(function () use ($assetId) {
            $asset = $this->assetRepository->getById($assetId);
            $totalDepreciation = $this->depreciationRepository->getTotalDepreciation($assetId);
            $currentBookValue = $this->depreciationRepository->getCurrentBookValue($assetId);

            $annualDepreciation = $asset->salvage_value !== null && $asset->useful_life > 0
                ? ($asset->purchase_cost - $asset->salvage_value) / $asset->useful_life
                : 0;

            $newDepreciation = $this->depreciationRepository->recordDepreciation([
                'asset_id' => $assetId,
                'depreciation_method' => $asset->depreciation_method ?? 'straight_line',
                'depreciation_amount' => round($annualDepreciation, 2),
                'year' => now()->year,
                'period' => now()->month,
            ]);

            $this->logActivity('asset_depreciation_calculated', $newDepreciation);

            return [
                'asset' => $asset->toArray(),
                'purchase_cost' => $asset->purchase_cost,
                'total_depreciation' => $totalDepreciation + $annualDepreciation,
                'current_book_value' => $currentBookValue - $annualDepreciation,
                'annual_depreciation' => round($annualDepreciation, 2),
            ];
        });
    }

    public function conductAudit(int $assetId, array $auditData): Asset
    {
        return DB::transaction(function () use ($assetId, $auditData) {
            $asset = $this->assetRepository->getById($assetId);

            $asset = $this->assetRepository->update($assetId, [
                'last_audit_date' => now(),
                'audit_notes' => $auditData['notes'] ?? null,
                'audit_condition' => $auditData['condition'] ?? $asset->audit_condition,
                'status' => $auditData['status'] ?? $asset->status,
            ]);

            $this->logActivity('asset_audit_conducted', $asset);

            return $asset;
        });
    }

    public function getAssetReport(int $assetId): array
    {
        $asset = $this->assetRepository->getById($assetId);
        $allocations = $this->allocationRepository->getAllocationHistory($assetId);
        $maintenanceRecords = $this->maintenanceRepository->findByAsset($assetId);
        $depreciationSchedule = $this->depreciationRepository->getDepreciationSchedule($assetId);

        $report = [
            'asset' => $asset->toArray(),
            'allocation_history' => $allocations,
            'maintenance_history' => $maintenanceRecords,
            'depreciation_schedule' => $depreciationSchedule,
            'total_maintenance_cost' => $maintenanceRecords->sum('cost'),
            'current_allocation' => $this->allocationRepository->getCurrentAllocation($assetId),
        ];

        $this->logActivity('asset_report_viewed', $report);

        return $report;
    }
}

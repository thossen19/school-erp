<?php

namespace App\Services\Transport;

use App\Contracts\RepositoryInterface;
use App\Models\Transport\TransportTracking;
use App\Repositories\Transport\TransportTrackingRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TrackingService extends BaseService
{
    protected TransportTrackingRepository $trackingRepository;

    public function __construct(TransportTrackingRepository $trackingRepository)
    {
        parent::__construct();
        $this->trackingRepository = $trackingRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->trackingRepository;
    }

    public function updateLocation(array $data): TransportTracking
    {
        return DB::transaction(function () use ($data) {
            $data['tracking_date'] = $data['tracking_date'] ?? now();
            $data['tracked_at'] = $data['tracked_at'] ?? now();

            $tracking = $this->trackingRepository->recordLocation($data);

            $this->logActivity('vehicle_location_updated', $tracking);

            return $tracking;
        });
    }

    public function getVehicleLocation(int $vehicleId): ?TransportTracking
    {
        return $this->trackingRepository->getLatestLocation($vehicleId);
    }

    public function getRouteHistory(int $vehicleId, string $date): Collection
    {
        return $this->trackingRepository->getVehicleHistory($vehicleId, $date);
    }
}

<?php

namespace App\Services\Transport;

use App\Contracts\RepositoryInterface;
use App\Models\Transport\TransportRoute;
use App\Repositories\Transport\TransportRouteRepository;
use App\Repositories\Transport\TransportVehicleRepository;
use App\Repositories\Transport\TransportDriverRepository;
use App\Repositories\Transport\TransportAllocationRepository;
use App\Repositories\Transport\TransportTrackingRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransportService extends BaseService
{
    protected TransportRouteRepository $routeRepository;
    protected TransportVehicleRepository $vehicleRepository;
    protected TransportDriverRepository $driverRepository;
    protected TransportAllocationRepository $allocationRepository;
    protected TransportTrackingRepository $trackingRepository;

    public function __construct(
        TransportRouteRepository $routeRepository,
        TransportVehicleRepository $vehicleRepository,
        TransportDriverRepository $driverRepository,
        TransportAllocationRepository $allocationRepository,
        TransportTrackingRepository $trackingRepository
    ) {
        parent::__construct();
        $this->routeRepository = $routeRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->driverRepository = $driverRepository;
        $this->allocationRepository = $allocationRepository;
        $this->trackingRepository = $trackingRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->routeRepository;
    }

    public function createRoute(array $data): TransportRoute
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] = $data['is_active'] ?? true;
            $route = $this->routeRepository->create($data);
            $this->logActivity('transport_route_created', $route);
            return $route;
        });
    }

    public function manageVehicle(array $data): \App\Models\Transport\TransportVehicle
    {
        return DB::transaction(function () use ($data) {
            $vehicle = $this->vehicleRepository->create($data);
            $this->logActivity('transport_vehicle_managed', $vehicle);
            return $vehicle;
        });
    }

    public function assignDriver(int $vehicleId, int $driverId): \App\Models\Transport\TransportDriver
    {
        return DB::transaction(function () use ($vehicleId, $driverId) {
            $driver = $this->driverRepository->getById($driverId);
            $this->driverRepository->assignVehicle($driverId, $vehicleId);
            $this->logActivity('driver_assigned_to_vehicle', [
                'driver_id' => $driverId,
                'vehicle_id' => $vehicleId,
            ]);
            return $driver->fresh();
        });
    }

    public function allocateStudent(int $studentId, int $routeId, int $vehicleId, array $data = []): \App\Models\Transport\TransportAllocation
    {
        return DB::transaction(function () use ($studentId, $routeId, $vehicleId, $data) {
            $allocation = $this->allocationRepository->allocateStudent($studentId, $routeId, $vehicleId, $data);
            $this->logActivity('student_allocated_to_transport', $allocation);
            return $allocation;
        });
    }

    public function getRouteReport(int $routeId): array
    {
        $route = $this->routeRepository->getRouteWithAllocations($routeId);
        $allocations = $route->allocations ?? collect();

        $report = [
            'route' => $route->toArray(),
            'total_students' => $allocations->count(),
            'active_students' => $allocations->where('status', 'active')->count(),
            'vehicle' => $route->vehicle,
            'driver' => $route->driver,
        ];

        $this->logActivity('transport_route_report_viewed', $report);

        return $report;
    }

    public function getLiveTracking(int $vehicleId): ?\App\Models\Transport\TransportTracking
    {
        $location = $this->trackingRepository->getLatestLocation($vehicleId);

        $this->logActivity('live_tracking_viewed', ['vehicle_id' => $vehicleId]);

        return $location;
    }

    public function sendParentNotification(int $studentId, string $message): bool
    {
        try {
            activity()->causedBy(auth()->user())->withProperties([
                    'student_id' => $studentId,
                    'message' => $message,
                    'sent_at' => now(),
                ])->event('transport_parent_notification')->log("TransportService: Parent notification sent for student {$studentId}");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('TransportService@sendParentNotification: ' . $e->getMessage());
            return false;
        }
    }
}

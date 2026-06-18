<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreTransportRouteRequest;
use App\Models\Transport\TransportRoute;
use App\Services\Transport\TransportService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportRouteController extends Controller
{
    use ApiResponseTrait;

    protected TransportService $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    public function index(Request $request): JsonResponse
    {
        $routes = TransportRoute::withCount('stops', 'allocations')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('route_name', 'like', "%{$request->search}%")->orWhere('route_no', 'like', "%{$request->search}%"))->orderBy('route_name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($routes, 'Transport routes retrieved');
    }

    public function store(StoreTransportRouteRequest $request): JsonResponse
    {
        $route = $this->transportService->createRoute($request->validated());
        return $this->createdResponse($route->load('stops'), 'Transport route created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            TransportRoute::with('stops', 'vehicle', 'driver', 'allocations.student')->findOrFail($id),
            'Transport route retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $route = TransportRoute::findOrFail($id);
        $validated = $request->validate([
            'route_name' => 'sometimes|string|max:255',
            'start_point' => 'sometimes|string|max:255',
            'end_point' => 'sometimes|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ]);
        $route->update($validated);
        return $this->updatedResponse($route->fresh()->load('stops'), 'Transport route updated');
    }

    public function destroy(int $id): JsonResponse
    {
        TransportRoute::findOrFail($id)->delete();
        return $this->deletedResponse('Transport route deleted');
    }

    public function getStops(int $routeId): JsonResponse
    {
        $route = TransportRoute::with('stops')->findOrFail($routeId);
        return $this->successResponse($route->stops, 'Route stops retrieved');
    }
}

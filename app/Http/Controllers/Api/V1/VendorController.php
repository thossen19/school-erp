<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Vendor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $vendors = Vendor::withCount('purchaseOrders')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('company_name', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%"))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($vendors, 'Vendors retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'gst_no' => 'nullable|string|max:50',
            'pan_no' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,inactive,blacklisted',
            'notes' => 'nullable|string|max:500',
        ]);

        $vendor = Vendor::create($validated);
        return $this->createdResponse($vendor, 'Vendor created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Vendor::with('purchaseOrders')->findOrFail($id),
            'Vendor retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'gst_no' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,inactive,blacklisted',
        ]);
        $vendor->update($validated);
        return $this->updatedResponse($vendor->fresh(), 'Vendor updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Vendor::findOrFail($id)->delete();
        return $this->deletedResponse('Vendor deleted');
    }
}

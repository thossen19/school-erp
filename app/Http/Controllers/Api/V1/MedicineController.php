<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Health\Medicine;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $medicines = Medicine::when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('generic_name', 'like', "%{$request->search}%"))->when($request->category, fn($q) => $q->where('category', $request->category))->when($request->stock_status, fn($q) => $q->where('stock_status', $request->stock_status))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($medicines, 'Medicines retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|in:tablet,capsule,syrup,injection,cream,ointment,drop,inhaler,other',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'storage_conditions' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $medicine = Medicine::create($validated);
        return $this->createdResponse($medicine, 'Medicine created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Medicine::findOrFail($id), 'Medicine retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $medicine = Medicine::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'sometimes|string|in:tablet,capsule,syrup,injection,cream,ointment,drop,inhaler,other',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'unit' => 'sometimes|string|max:50',
            'quantity' => 'sometimes|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);
        $medicine->update($validated);
        return $this->updatedResponse($medicine->fresh(), 'Medicine updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Medicine::findOrFail($id)->delete();
        return $this->deletedResponse('Medicine deleted');
    }
}

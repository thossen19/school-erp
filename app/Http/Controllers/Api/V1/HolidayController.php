<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Holiday;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $holidays = Holiday::when($request->year, fn($q) => $q->whereYear('date', $request->year))->when($request->type, fn($q) => $q->where('type', $request->type))->orderBy('date')->paginate($request->per_page ?? 50);

        return $this->paginatedResponse($holidays, 'Holidays retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
            'type' => 'required|string|in:public,religious,school,event',
            'description' => 'nullable|string|max:500',
            'is_optional' => 'boolean',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
        ]);

        $holiday = Holiday::create($validated);
        return $this->createdResponse($holiday, 'Holiday created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Holiday::findOrFail($id), 'Holiday retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $holiday = Holiday::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'date' => 'sometimes|date|unique:holidays,date,' . $id,
            'type' => 'sometimes|string|in:public,religious,school,event',
            'description' => 'nullable|string|max:500',
            'is_optional' => 'boolean',
        ]);
        $holiday->update($validated);
        return $this->updatedResponse($holiday->fresh(), 'Holiday updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Holiday::findOrFail($id)->delete();
        return $this->deletedResponse('Holiday deleted');
    }
}

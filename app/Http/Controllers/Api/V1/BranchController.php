<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BranchController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $branches = Branch::withCount('users', 'students', 'employees')->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($branches, 'Branches retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'school_id' => 'required|integer|exists:schools,id',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'boolean',
        ]);

        $branch = Branch::create($validated);
        return $this->createdResponse($branch, 'Branch created');
    }

    public function show(int $id): JsonResponse
    {
        $branch = Branch::with('school')->withCount('users', 'students', 'employees')->findOrFail($id);
        return $this->successResponse($branch, 'Branch retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:branches,code,' . $id,
            'school_id' => 'sometimes|integer|exists:schools,id',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'boolean',
        ]);
        $branch->update($validated);
        return $this->updatedResponse($branch->fresh(), 'Branch updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return $this->deletedResponse('Branch deleted');
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alumni\Alumni;
use App\Services\Alumni\AlumniService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    use ApiResponseTrait;

    protected AlumniService $alumniService;

    public function __construct(AlumniService $alumniService)
    {
        $this->alumniService = $alumniService;
    }

    public function index(Request $request): JsonResponse
    {
        $alumni = Alumni::with('student:id,first_name,last_name')->when($request->batch_year, fn($q) => $q->where('batch_year', $request->batch_year))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->is_verified, fn($q) => $q->where('is_verified', $request->boolean('is_verified')))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%");
            }))->orderBy('batch_year', 'desc')->orderBy('first_name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($alumni, 'Alumni retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'nullable|integer|exists:students,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:alumni,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|in:male,female,other',
            'batch_year' => 'required|integer|min:1950|max:' . date('Y'),
            'graduation_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'current_occupation' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'linkedin_url' => 'nullable|url|max:500',
            'facebook_url' => 'nullable|url|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $alumnus = Alumni::create($validated);

        if ($request->hasFile('photo')) {
            $alumnus->update(['photo' => $request->file('photo')->store('alumni', 'public')]);
        }

        return $this->createdResponse($alumnus, 'Alumni record created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Alumni::with('student', 'eventAttendees.event', 'donations')->findOrFail($id),
            'Alumni record retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $alumnus = Alumni::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255|unique:alumni,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'current_occupation' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'linkedin_url' => 'nullable|url|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $alumnus->update($validated);
        return $this->updatedResponse($alumnus->fresh(), 'Alumni record updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Alumni::findOrFail($id)->delete();
        return $this->deletedResponse('Alumni record deleted');
    }

    public function directory(Request $request): JsonResponse
    {
        $alumni = Alumni::where('status', 'active')->where('is_verified', true)->when($request->batch_year, fn($q) => $q->where('batch_year', $request->batch_year))->when($request->city, fn($q) => $q->where('city', $request->city))->orderBy('batch_year', 'desc')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'batch_year', 'current_occupation', 'current_company', 'city', 'photo']);

        return $this->successResponse($alumni, 'Alumni directory');
    }

    public function verify(int $id): JsonResponse
    {
        $alumnus = Alumni::findOrFail($id);
        $alumnus->update(['is_verified' => true, 'verified_at' => now()]);
        return $this->successResponse($alumnus, 'Alumni verified');
    }
}

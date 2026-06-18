<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\School;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SchoolController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => $user->user_type === 'super_admin');
        $schools = School::withCount('branches', 'users', 'students')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($schools, 'Schools retrieved');
    }

    public function store(StoreSchoolRequest $request): JsonResponse
    {
        Gate::allowIf(fn($user) => $user->user_type === 'super_admin');
        $school = School::create($request->validated());

        if ($request->hasFile('logo')) {
            $school->update(['logo' => $request->file('logo')->store('schools/logos', 'public')]);
        }
        if ($request->hasFile('favicon')) {
            $school->update(['favicon' => $request->file('favicon')->store('schools/favicons', 'public')]);
        }

        return $this->createdResponse($school, 'School created');
    }

    public function show(int $id): JsonResponse
    {
        $school = School::with('branches', 'academicYears')->findOrFail($id);
        return $this->successResponse($school, 'School retrieved');
    }

    public function update(UpdateSchoolRequest $request, int $id): JsonResponse
    {
        $school = School::findOrFail($id);
        $school->update($request->validated());

        if ($request->hasFile('logo')) {
            $school->update(['logo' => $request->file('logo')->store('schools/logos', 'public')]);
        }
        if ($request->hasFile('favicon')) {
            $school->update(['favicon' => $request->file('favicon')->store('schools/favicons', 'public')]);
        }

        return $this->updatedResponse($school->fresh(), 'School updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::allowIf(fn($user) => $user->user_type === 'super_admin');
        School::findOrFail($id)->delete();
        return $this->deletedResponse('School deleted');
    }

    public function manageBranches(Request $request, int $id): JsonResponse
    {
        $school = School::findOrFail($id);
        $branch = Branch::findOrFail($request->branch_id);

        if ($request->action === 'attach') {
            $branch->update(['school_id' => $id]);
        } elseif ($request->action === 'detach') {
            $branch->update(['school_id' => null]);
        }

        return $this->successResponse($school->branches()->get(), 'Branch management updated');
    }

    public function manageAcademicYears(Request $request, int $id): JsonResponse
    {
        $school = School::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_current' => 'boolean',
            ]);

            if ($request->boolean('is_current')) {
                AcademicYear::where('school_id', $id)->update(['is_current' => false]);
            }

            $year = $school->academicYears()->create($request->only('name', 'start_date', 'end_date', 'is_current'));

            return $this->createdResponse($year, 'Academic year created');
        }

        return $this->successResponse($school->academicYears()->get(), 'Academic years retrieved');
    }

    public function getSettings(int $id): JsonResponse
    {
        $school = School::findOrFail($id);
        return $this->successResponse($school->settings ?? [], 'School settings retrieved');
    }

    public function updateSettings(Request $request, int $id): JsonResponse
    {
        $school = School::findOrFail($id);
        $school->update(['settings' => array_merge($school->settings ?? [], $request->all())]);
        return $this->updatedResponse($school->settings, 'School settings updated');
    }
}

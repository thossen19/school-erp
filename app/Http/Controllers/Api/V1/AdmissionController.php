<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admission\ProcessAdmissionRequest;
use App\Http\Requests\Admission\StoreAdmissionFormRequest;
use App\Models\Admission\AdmissionEnquiry;
use App\Models\Admission\AdmissionForm;
use App\Models\Admission\MeritList;
use App\Services\Admission\AdmissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdmissionController extends Controller
{
    use ApiResponseTrait;

    protected AdmissionService $admissionService;

    public function __construct(AdmissionService $admissionService)
    {
        $this->admissionService = $admissionService;
    }

    public function index(Request $request): JsonResponse
    {
        $forms = AdmissionForm::with('class', 'academicYear', 'branch')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('application_no', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%");
            }))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($forms, 'Admission forms retrieved');
    }

    public function store(StoreAdmissionFormRequest $request): JsonResponse
    {
        $form = $this->admissionService->createApplication($request->validated());

        if ($request->hasFile('photo')) {
            $form->update(['photo' => $request->file('photo')->store('admissions/photos', 'public')]);
        }

        return $this->createdResponse($form->load('class', 'academicYear'), 'Application submitted');
    }

    public function show(int $id): JsonResponse
    {
        $form = AdmissionForm::with('class', 'academicYear', 'branch', 'examResults', 'meritLists')->findOrFail($id);
        return $this->successResponse($form, 'Application retrieved');
    }

    public function update(StoreAdmissionFormRequest $request, int $id): JsonResponse
    {
        $form = AdmissionForm::findOrFail($id);
        $form->update($request->validated());
        return $this->updatedResponse($form->fresh()->load('class', 'academicYear'), 'Application updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AdmissionForm::findOrFail($id)->delete();
        return $this->deletedResponse('Application deleted');
    }

    public function applications(Request $request): JsonResponse
    {
        $query = AdmissionForm::with('class', 'academicYear');

        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);

        return $this->paginatedResponse($query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15));
    }

    public function approve(ProcessAdmissionRequest $request, int $id): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['school_admin', 'principal', 'admin']));
        $form = $this->admissionService->approveAdmission($id, $request->validated());
        return $this->successResponse($form->load('student'), 'Admission approved');
    }

    public function reject(ProcessAdmissionRequest $request, int $id): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['school_admin', 'principal', 'admin']));
        $form = AdmissionForm::findOrFail($id);
        $form->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason, 'reviewed_at' => now()]);
        return $this->successResponse($form, 'Application rejected');
    }

    public function generateMeritList(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['school_admin', 'principal']));
        $request->validate(['class_id' => 'required|integer|exists:classes,id', 'exam_id' => 'required|integer|exists:entrance_exams,id']);

        $meritList = $this->admissionService->generateMeritList($request->class_id, $request->exam_id);

        if ($request->has('publish') && $request->boolean('publish')) {
            MeritList::where('class_id', $request->class_id)->where('exam_id', $request->exam_id)->update(['is_published' => true]);
        }

        return $this->successResponse($meritList, 'Merit list generated');
    }

    public function scheduleInterview(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'interview_date' => 'required|date|after:today',
            'interview_time' => 'required|date_format:H:i',
            'interview_location' => 'nullable|string|max:255',
            'interviewer' => 'nullable|string|max:255',
        ]);

        $form = AdmissionForm::findOrFail($id);
        $form->update([
            'status' => 'interview_scheduled',
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'interview_location' => $request->interview_location,
            'interviewer' => $request->interviewer,
            'scheduled_at' => now(),
        ]);

        return $this->successResponse($form, 'Interview scheduled');
    }

    public function addToWaitingList(Request $request, int $id): JsonResponse
    {
        $form = AdmissionForm::findOrFail($id);
        $request->validate(['priority' => 'nullable|integer|min:1', 'remarks' => 'nullable|string|max:500']);

        $this->admissionService->addToWaitingList($id, $request->priority ?? 1, $request->remarks);
        $form->update(['status' => 'waiting_list']);

        return $this->successResponse($form, 'Added to waiting list');
    }

    public function getEnquiries(Request $request): JsonResponse
    {
        $enquiries = AdmissionEnquiry::with('class')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->source, fn($q) => $q->where('source', $request->source))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($enquiries, 'Admission enquiries retrieved');
    }

    public function getStatistics(Request $request): JsonResponse
    {
        $stats = $this->admissionService->getAdmissionStats($request->get('school_id'), $request->get('academic_year_id'));
        return $this->successResponse($stats, 'Admission statistics');
    }
}

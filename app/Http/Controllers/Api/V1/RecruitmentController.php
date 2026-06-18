<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hr\JobApplication;
use App\Models\Hr\Recruitment;
use App\Services\Hr\RecruitmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    use ApiResponseTrait;

    protected RecruitmentService $recruitmentService;

    public function __construct(RecruitmentService $recruitmentService)
    {
        $this->recruitmentService = $recruitmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $postings = Recruitment::with('department', 'designation')->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->type, fn($q) => $q->where('type', $request->type))->orderBy('posting_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($postings, 'Recruitment postings retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'type' => 'required|string|in:full_time,part_time,contract,temporary,internship',
            'vacancies' => 'required|integer|min:1',
            'description' => 'required|string|max:5000',
            'requirements' => 'nullable|string|max:5000',
            'responsibilities' => 'nullable|string|max:5000',
            'salary_range' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'posting_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:posting_date',
            'status' => 'nullable|string|in:open,closed,on_hold',
            'is_public' => 'boolean',
        ]);

        $posting = Recruitment::create($validated);
        return $this->createdResponse($posting->load('department', 'designation'), 'Job posting created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Recruitment::with('department', 'designation', 'applications')->findOrFail($id),
            'Job posting retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $posting = Recruitment::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:full_time,part_time,contract,temporary,internship',
            'vacancies' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string|max:5000',
            'requirements' => 'nullable|string|max:5000',
            'salary_range' => 'nullable|string|max:100',
            'closing_date' => 'sometimes|date|after_or_equal:posting_date',
            'status' => 'nullable|string|in:open,closed,on_hold',
        ]);
        $posting->update($validated);
        return $this->updatedResponse($posting->fresh()->load('department', 'designation'), 'Job posting updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Recruitment::findOrFail($id)->delete();
        return $this->deletedResponse('Job posting deleted');
    }

    public function receiveApplication(Request $request, int $postingId): JsonResponse
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'qualification' => 'nullable|string|max:2000',
            'experience_years' => 'nullable|integer|min:0',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric|min:0',
            'cover_letter' => 'nullable|string|max:5000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'portfolio' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['resume_path'] = $request->file('resume')->store('recruitment/resumes', 'public');
        if ($request->hasFile('portfolio')) {
            $validated['portfolio_path'] = $request->file('portfolio')->store('recruitment/portfolios', 'public');
        }

        $application = JobApplication::create(array_merge($validated, [
            'recruitment_id' => $postingId,
            'status' => 'received',
            'applied_at' => now(),
        ]));

        return $this->createdResponse($application, 'Application received');
    }

    public function shortlist(Request $request, int $postingId, int $applicationId): JsonResponse
    {
        $application = JobApplication::where('recruitment_id', $postingId)->findOrFail($applicationId);
        $application->update(['status' => 'shortlisted', 'shortlisted_at' => now()]);
        return $this->successResponse($application, 'Applicant shortlisted');
    }

    public function scheduleInterview(Request $request, int $postingId, int $applicationId): JsonResponse
    {
        $request->validate([
            'interview_date' => 'required|date|after:today',
            'interview_time' => 'required|date_format:H:i',
            'interview_type' => 'nullable|string|in:in_person,phone,video',
            'interview_location' => 'nullable|string|max:255',
            'interview_link' => 'nullable|url|max:500',
            'interviewer' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::where('recruitment_id', $postingId)->findOrFail($applicationId);
        $application->update(array_merge($request->validated(), ['status' => 'interview_scheduled']));

        return $this->successResponse($application, 'Interview scheduled');
    }

    public function makeOffer(Request $request, int $postingId, int $applicationId): JsonResponse
    {
        $request->validate([
            'offer_amount' => 'required|numeric|min:0',
            'offer_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'joining_date' => 'required|date|after:today',
            'terms' => 'nullable|string|max:5000',
        ]);

        $application = JobApplication::where('recruitment_id', $postingId)->findOrFail($applicationId);
        $data = array_merge($request->except('offer_letter'), ['status' => 'offer_made']);

        if ($request->hasFile('offer_letter')) {
            $data['offer_letter_path'] = $request->file('offer_letter')->store('recruitment/offers', 'public');
        }

        $application->update($data);
        return $this->successResponse($application, 'Offer made');
    }
}

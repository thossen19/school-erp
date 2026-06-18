<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admission\AdmissionEnquiry;
use App\Services\Admission\AdmissionEnquiryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdmissionEnquiryController extends Controller
{
    use ApiResponseTrait;

    protected AdmissionEnquiryService $enquiryService;

    public function __construct(AdmissionEnquiryService $enquiryService)
    {
        $this->enquiryService = $enquiryService;
    }

    public function index(Request $request): JsonResponse
    {
        $enquiries = AdmissionEnquiry::with('class')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
            }))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($enquiries, 'Enquiries retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
        ]);

        $enquiry = AdmissionEnquiry::create($validated);
        return $this->createdResponse($enquiry->load('class'), 'Enquiry created');
    }

    public function show(int $id): JsonResponse
    {
        $enquiry = AdmissionEnquiry::with('class')->findOrFail($id);
        return $this->successResponse($enquiry, 'Enquiry retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $enquiry = AdmissionEnquiry::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email|max:255',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|string|in:new,contacted,converted,closed',
        ]);
        $enquiry->update($validated);
        return $this->updatedResponse($enquiry->fresh()->load('class'), 'Enquiry updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AdmissionEnquiry::findOrFail($id)->delete();
        return $this->deletedResponse('Enquiry deleted');
    }

    public function convertToApplication(Request $request, int $id): JsonResponse
    {
        $enquiry = AdmissionEnquiry::findOrFail($id);
        $application = $this->enquiryService->convertToApplication($enquiry, $request->all());
        $enquiry->update(['status' => 'converted']);
        return $this->createdResponse($application, 'Enquiry converted to application');
    }

    public function followUp(Request $request, int $id): JsonResponse
    {
        $request->validate(['follow_up_date' => 'required|date|after:today', 'notes' => 'nullable|string|max:1000']);
        $enquiry = AdmissionEnquiry::findOrFail($id);
        $enquiry->update(['follow_up_date' => $request->follow_up_date, 'notes' => $request->notes ?? $enquiry->notes]);
        return $this->successResponse($enquiry, 'Follow-up scheduled');
    }
}

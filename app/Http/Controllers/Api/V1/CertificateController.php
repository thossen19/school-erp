<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Certificate\Certificate;
use App\Services\Certificate\CertificateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    use ApiResponseTrait;

    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request): JsonResponse
    {
        $certificates = Certificate::with('type', 'template')->when($request->type_id, fn($q) => $q->where('certificate_type_id', $request->type_id))->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('issue_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($certificates, 'Certificates retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'certificate_type_id' => 'required|integer|exists:certificate_types,id',
            'template_id' => 'required|integer|exists:certificate_templates,id',
            'student_id' => 'nullable|integer|exists:students,id',
            'employee_id' => 'nullable|integer|exists:employees,id',
            'issue_date' => 'required|date',
            'certificate_no' => 'required|string|max:50|unique:certificates,certificate_no',
            'purpose' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
            'custom_fields' => 'nullable|array',
        ]);

        $certificate = Certificate::create($validated);
        return $this->createdResponse($certificate->load('type', 'template'), 'Certificate created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Certificate::with('type', 'template', 'student', 'employee')->findOrFail($id),
            'Certificate retrieved'
        );
    }

    public function generate(int $id): JsonResponse
    {
        $certificate = Certificate::with('type', 'template', 'student', 'employee')->findOrFail($id);
        $output = $this->certificateService->generateCertificate($certificate);
        $certificate->update(['status' => 'generated', 'generated_at' => now()]);
        return $this->successResponse($output, 'Certificate generated');
    }

    public function verify(string $certificateNo): JsonResponse
    {
        $certificate = Certificate::where('certificate_no', $certificateNo)->with('type', 'student', 'employee')->first();
        if (!$certificate) {
            return $this->notFoundResponse('Certificate not found');
        }
        return $this->successResponse([
            'is_valid' => $certificate->status === 'generated',
            'certificate' => $certificate,
        ], 'Certificate verification completed');
    }

    public function download(int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);
        $file = $this->certificateService->downloadCertificate($certificate);
        return response()->download($file['path'], $file['name'])->deleteFileAfterSend();
    }
}

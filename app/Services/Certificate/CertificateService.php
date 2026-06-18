<?php

namespace App\Services\Certificate;

use App\Contracts\RepositoryInterface;
use App\Models\Certificate\Certificate;
use App\Repositories\Certificate\CertificateRepository;
use App\Repositories\Certificate\CertificateTemplateRepository;
use App\Repositories\Certificate\CertificateTypeRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CertificateService extends BaseService
{
    protected CertificateRepository $certificateRepository;
    protected CertificateTemplateRepository $templateRepository;
    protected CertificateTypeRepository $typeRepository;

    public function __construct(
        CertificateRepository $certificateRepository,
        CertificateTemplateRepository $templateRepository,
        CertificateTypeRepository $typeRepository
    ) {
        parent::__construct();
        $this->certificateRepository = $certificateRepository;
        $this->templateRepository = $templateRepository;
        $this->typeRepository = $typeRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->certificateRepository;
    }

    public function generateCertificate(array $data): Certificate
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['certificate_no'])) {
                $data['certificate_no'] = $this->certificateRepository->generateCertificateNo();
            }

            $data['status'] = $data['status'] ?? 'draft';
            $data['issue_date'] = $data['issue_date'] ?? now();

            $certificate = $this->certificateRepository->create($data);

            $this->logActivity('certificate_generated', $certificate);

            return $certificate;
        });
    }

    public function verifyCertificate(string $certificateNo): ?Certificate
    {
        $certificate = $this->certificateRepository->query()->where('certificate_no', $certificateNo)->first();

        if ($certificate) {
            $this->logActivity('certificate_verified', $certificate);
        }

        return $certificate;
    }

    public function generateQRCode(int $certificateId): string
    {
        $certificate = $this->certificateRepository->getById($certificateId);

        $qrData = json_encode([
            'certificate_no' => $certificate->certificate_no,
            'student_name' => $certificate->student->full_name ?? $certificate->holder_name,
            'issue_date' => $certificate->issue_date->format('Y-m-d'),
            'type' => $certificate->certificateType->name,
        ]);

        $qrCode = base64_encode($qrData);

        $this->certificateRepository->update($certificateId, ['qr_code' => $qrCode]);

        $this->logActivity('certificate_qr_generated', $certificate);

        return $qrCode;
    }

    public function addDigitalSignature(int $certificateId, string $signatureData): Certificate
    {
        return DB::transaction(function () use ($certificateId, $signatureData) {
            $certificate = $this->certificateRepository->getById($certificateId);

            $certificate = $this->certificateRepository->update($certificateId, [
                'digital_signature' => $signatureData,
                'is_signed' => true,
                'signed_at' => now(),
                'signed_by' => auth()->id(),
            ]);

            $this->logActivity('certificate_signed', $certificate);

            return $certificate;
        });
    }

    public function createFromTemplate(int $templateId, array $data): Certificate
    {
        return DB::transaction(function () use ($templateId, $data) {
            $template = $this->templateRepository->getById($templateId);

            $variables = is_string($template->variables)
                ? json_decode($template->variables, true)
                : ($template->variables ?? []);

            $content = $template->content;
            foreach ($variables as $key => $default) {
                $value = $data['variables'][$key] ?? $default;
                $content = str_replace("{{{$key}}}", $value, $content);
            }

            $certificateData = array_merge($data, [
                'certificate_type_id' => $template->certificate_type_id,
                'template_id' => $templateId,
                'content' => $content,
                'certificate_no' => $data['certificate_no'] ?? $this->certificateRepository->generateCertificateNo(),
                'status' => 'draft',
            ]);

            $certificate = $this->certificateRepository->create($certificateData);

            $this->logActivity('certificate_created_from_template', $certificate);

            return $certificate;
        });
    }
}

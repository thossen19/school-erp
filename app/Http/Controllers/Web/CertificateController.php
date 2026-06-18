<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    private function getSchoolId()
    {
        return session('school_id', 1);
    }

    // ============ Type-specific certificate pages ============

    private function typeIndex(Request $request, string $type, string $view)
    {
        $schoolId = $this->getSchoolId();

        $certificates = DB::table('certificates')
            ->leftJoin('students', 'certificates.student_id', '=', 'students.id')
            ->leftJoin('certificate_templates', 'certificates.template_id', '=', 'certificate_templates.id')
            ->where('certificates.school_id', $schoolId)
            ->where('certificates.certificate_type', $type)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('certificates.certificate_number', 'like', "%{$v}%")
                  ->orWhere('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%");
            }))
            ->when($request->status, fn($q, $v) => $q->where('certificates.status', $v))
            ->select('certificates.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'certificate_templates.name as template_name')
            ->orderBy('certificates.issue_date', 'desc')
            ->paginate(20);

        $students = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);
        $templates = DB::table('certificate_templates')->where('school_id', $schoolId)->where('type', $type)->where('status', true)->orderBy('name')->get(['id', 'name']);

        return view($view, compact('certificates', 'students', 'templates', 'type'));
    }

    public function transfer(Request $request)
    {
        return $this->typeIndex($request, 'Transfer Certificate', 'certificates.type-index');
    }

    public function character(Request $request)
    {
        return $this->typeIndex($request, 'Character Certificate', 'certificates.type-index');
    }

    public function bonafide(Request $request)
    {
        return $this->typeIndex($request, 'Bonafide Certificate', 'certificates.type-index');
    }

    public function courseCompletion(Request $request)
    {
        return $this->typeIndex($request, 'Course Completion Certificate', 'certificates.type-index');
    }

    public function customCertificates(Request $request)
    {
        $schoolId = $this->getSchoolId();
        $standardTypes = ['Transfer Certificate', 'Character Certificate', 'Bonafide Certificate', 'Course Completion Certificate'];

        $certificates = DB::table('certificates')
            ->leftJoin('students', 'certificates.student_id', '=', 'students.id')
            ->leftJoin('certificate_templates', 'certificates.template_id', '=', 'certificate_templates.id')
            ->where('certificates.school_id', $schoolId)
            ->whereNotIn('certificates.certificate_type', $standardTypes)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('certificates.certificate_number', 'like', "%{$v}%")
                  ->orWhere('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%");
            }))
            ->when($request->status, fn($q, $v) => $q->where('certificates.status', $v))
            ->select('certificates.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'certificate_templates.name as template_name')
            ->orderBy('certificates.issue_date', 'desc')
            ->paginate(20);

        $students = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);
        $types = DB::table('certificate_types')->where('school_id', $schoolId)->whereNotIn('name', $standardTypes)->orderBy('name')->get(['id', 'name', 'code']);
        $templates = DB::table('certificate_templates')->where('school_id', $schoolId)->where('status', true)->orderBy('name')->get(['id', 'name', 'type']);

        return view('certificates.custom', compact('certificates', 'students', 'types', 'templates'));
    }

    // ============ Issue / Store certificate ============

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'certificate_type' => 'required|string|max:100',
            'template_id' => 'nullable|integer|exists:certificate_templates,id',
            'issue_date' => 'required|date',
            'status' => 'nullable|string|in:active,draft,revoked',
        ]);

        $validated['school_id'] = $this->getSchoolId();
        $validated['certificate_number'] = $this->generateCertificateNumber();
        $validated['status'] ??= 'active';

        DB::table('certificates')->insert($validated);

        $route = match ($validated['certificate_type']) {
            'Transfer Certificate' => 'certificates.transfer',
            'Character Certificate' => 'certificates.character',
            'Bonafide Certificate' => 'certificates.bonafide',
            'Course Completion Certificate' => 'certificates.course-completion',
            default => 'certificates.custom',
        };

        return redirect()->route($route)->with('success', 'Certificate issued successfully');
    }

    private function generateCertificateNumber(): string
    {
        $prefix = 'CRT-' . date('Y') . '-';
        $last = DB::table('certificates')->where('certificate_number', 'like', $prefix . '%')->orderBy('id', 'desc')->value('certificate_number');
        $next = $last ? intval(substr($last, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'issue_date' => 'sometimes|date',
            'status' => 'sometimes|string|in:active,draft,revoked',
            'certificate_type' => 'sometimes|string|max:100',
        ]);

        DB::table('certificates')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->back()->with('success', 'Certificate updated');
    }

    public function destroy(int $id)
    {
        DB::table('certificates')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Certificate deleted');
    }

    // ============ QR Verification ============

    public function qrVerify(Request $request)
    {
        $certificate = null;
        if ($request->certificate_number) {
            $certificate = DB::table('certificates')
                ->leftJoin('students', 'certificates.student_id', '=', 'students.id')
                ->leftJoin('certificate_templates', 'certificates.template_id', '=', 'certificate_templates.id')
                ->where('certificates.certificate_number', $request->certificate_number)
                ->select('certificates.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'certificate_templates.name as template_name')
                ->first();
        }
        return view('certificates.qr-verify', compact('certificate'));
    }

    // ============ Digital Signature ============

    public function digitalSignature(Request $request)
    {
        $schoolId = $this->getSchoolId();

        $certificates = DB::table('certificates')
            ->leftJoin('students', 'certificates.student_id', '=', 'students.id')
            ->where('certificates.school_id', $schoolId)
            ->select('certificates.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy(DB::raw('CASE WHEN certificates.digital_signature IS NULL THEN 0 ELSE 1 END'))
            ->orderBy('certificates.issue_date', 'desc')
            ->paginate(20);

        return view('certificates.digital-signature', compact('certificates'));
    }

    public function addDigitalSignature(Request $request, int $id)
    {
        $request->validate([
            'signature_data' => 'required|string',
        ]);

        DB::table('certificates')->where('id', $id)->update([
            'digital_signature' => $request->signature_data,
            'verified' => true,
            'verified_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect()->route('certificates.digital-signature')->with('success', 'Digital signature added');
    }

    // ============ Certificate Templates ============

    public function templates(Request $request)
    {
        $schoolId = $this->getSchoolId();

        $templates = DB::table('certificate_templates')
            ->where('school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->orderBy('name')
            ->paginate(20);

        $types = DB::table('certificate_types')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return view('certificates.templates', compact('templates', 'types'));
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'content' => 'required|string',
            'layout' => 'nullable|string|max:20',
            'orientation' => 'nullable|string|max:20',
            'variables' => 'nullable|json',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $validated['school_id'] = $this->getSchoolId();
        $validated['variables'] = $request->filled('variables') ? $request->variables : null;
        $validated['is_default'] = $request->boolean('is_default');
        $validated['status'] = $request->boolean('status', true);

        DB::table('certificate_templates')->insert($validated);

        return redirect()->route('certificates.templates')->with('success', 'Template created');
    }

    public function updateTemplate(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'content' => 'required|string',
            'layout' => 'nullable|string|max:20',
            'orientation' => 'nullable|string|max:20',
            'variables' => 'nullable|json',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $validated['variables'] = $request->filled('variables') ? $request->variables : null;
        $validated['is_default'] = $request->boolean('is_default');
        $validated['status'] = $request->boolean('status', true);

        DB::table('certificate_templates')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('certificates.templates')->with('success', 'Template updated');
    }

    public function deleteTemplate(int $id)
    {
        DB::table('certificate_templates')->where('id', $id)->delete();
        return redirect()->route('certificates.templates')->with('success', 'Template deleted');
    }
}

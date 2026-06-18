<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_forms')->where('admission_forms.school_id', $schoolId)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->select('admission_forms.*', 'classes.name as class_name');

        if ($request->filled('status')) $query->where('admission_forms.status', $request->status);
        if ($request->filled('class_id')) $query->where('admission_forms.class_applying_for_id', $request->class_id);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('admission_forms.applicant_name', 'like', "%{$s}%")
                  ->orWhere('admission_forms.form_number', 'like', "%{$s}%")
                  ->orWhere('admission_forms.phone', 'like', "%{$s}%");
            });
        }

        $applications = $query->orderBy('admission_forms.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $enquiries = DB::table('admission_enquiries')->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('admissions.index', compact('applications', 'classes', 'enquiries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'birth_cert_no' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female,other',
            'class_applying_for_id' => 'nullable|integer',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $validated['school_id'] = 1;
        $validated['academic_year_id'] = DB::table('academic_years')->where('school_id', 1)->orderBy('id', 'desc')->value('id');
        $validated['applied_date'] = now()->toDateString();
        $validated['form_number'] = 'ADM-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        DB::table('admission_forms')->insert($validated);
        return redirect()->route('admissions.index')->with('success', 'Application created');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'birth_cert_no' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female,other',
            'class_applying_for_id' => 'nullable|integer',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.index')->with('success', 'Application updated');
    }

    public function destroy(int $id)
    {
        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.index')->with('success', 'Application deleted');
    }

    public function approve(int $id)
    {
        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)->update(['status' => 'approved']);
        return redirect()->route('admissions.index')->with('success', 'Application approved');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)
            ->update(['status' => 'rejected', 'remarks' => $request->rejection_reason]);
        return redirect()->route('admissions.index')->with('success', 'Application rejected');
    }

    public function entranceExam(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('entrance_exams')->where('entrance_exams.school_id', $schoolId)
            ->leftJoin('classes', 'entrance_exams.class_id', '=', 'classes.id')
            ->select('entrance_exams.*', 'classes.name as class_name');

        if ($request->filled('status')) $query->where('entrance_exams.status', $request->status);
        $exams = $query->orderBy('exam_date', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('admissions.entrance-exam', compact('exams', 'classes'));
    }

    public function storeEntranceExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'nullable|integer',
            'exam_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'description' => 'nullable|string|max:5000',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = $request->boolean('status');
        DB::table('entrance_exams')->insert($validated);
        return redirect()->route('admissions.entrance-exam')->with('success', 'Entrance exam created');
    }

    public function updateEntranceExam(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'nullable|integer',
            'exam_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'description' => 'nullable|string|max:5000',
        ]);
        $validated['status'] = $request->boolean('status');
        DB::table('entrance_exams')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.entrance-exam')->with('success', 'Entrance exam updated');
    }

    public function destroyEntranceExam(int $id)
    {
        DB::table('entrance_exams')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.entrance-exam')->with('success', 'Entrance exam deleted');
    }

    public function storeExamResult(Request $request)
    {
        $validated = $request->validate([
            'entrance_exam_id' => 'required|integer|exists:entrance_exams,id',
            'admission_form_id' => 'required|integer|exists:admission_forms,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|integer|min:1',
        ]);
        $validated['percentage'] = $validated['total_marks'] > 0 ? round(($validated['marks_obtained'] / $validated['total_marks']) * 100, 2) : 0;
        DB::table('entrance_exam_results')->updateOrInsert(
            ['entrance_exam_id' => $validated['entrance_exam_id'], 'admission_form_id' => $validated['admission_form_id']],
            $validated
        );
        return redirect()->back()->with('success', 'Result saved');
    }

    public function meritList(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('merit_lists')->where('merit_lists.school_id', $schoolId)
            ->leftJoin('classes', 'merit_lists.class_id', '=', 'classes.id')
            ->select('merit_lists.*', 'classes.name as class_name');

        if ($request->filled('status')) $query->where('merit_lists.status', $request->status);
        $meritLists = $query->orderBy('created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('admissions.merit-list', compact('meritLists', 'classes'));
    }

    public function storeMeritList(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'nullable|integer',
            'criteria' => 'nullable|string',
            'status' => 'nullable|string|in:draft,published',
        ]);
        $validated['school_id'] = 1;
        $validated['generated_by'] = auth()->id();
        $validated['generated_at'] = now();
        $validated['generated_date'] = now()->toDateString();
        DB::table('merit_lists')->insert($validated);
        return redirect()->route('admissions.merit-list')->with('success', 'Merit list created');
    }

    public function updateMeritList(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'nullable|integer',
            'criteria' => 'nullable|string',
            'status' => 'nullable|string|in:draft,published',
        ]);
        DB::table('merit_lists')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.merit-list')->with('success', 'Merit list updated');
    }

    public function destroyMeritList(int $id)
    {
        DB::table('merit_lists')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.merit-list')->with('success', 'Merit list deleted');
    }

    public function waitingList(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('waiting_lists')->where('waiting_lists.school_id', $schoolId)
            ->join('admission_forms', 'waiting_lists.admission_form_id', '=', 'admission_forms.id')
            ->select('waiting_lists.*', 'admission_forms.applicant_name', 'admission_forms.form_number', 'admission_forms.phone');
        if ($request->filled('status')) $query->where('waiting_lists.status', $request->status);
        $waitingLists = $query->orderBy('waiting_lists.rank')->paginate(15);
        $forms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'approved')->orderBy('applicant_name')->get();
        return view('admissions.waiting-list', compact('waitingLists', 'forms'));
    }

    public function storeWaitingList(Request $request)
    {
        $validated = $request->validate([
            'admission_form_id' => 'required|integer|exists:admission_forms,id',
            'rank' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:1000',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'waiting';
        DB::table('waiting_lists')->insert($validated);
        return redirect()->route('admissions.waiting-list')->with('success', 'Added to waiting list');
    }

    public function updateWaitingList(Request $request, int $id)
    {
        $validated = $request->validate([
            'rank' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:waiting,admitted,cancelled',
        ]);
        DB::table('waiting_lists')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.waiting-list')->with('success', 'Waiting list updated');
    }

    public function destroyWaitingList(int $id)
    {
        DB::table('waiting_lists')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.waiting-list')->with('success', 'Removed from waiting list');
    }

    // Enquiry CRUD
    public function storeEnquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'class_id' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:5000',
            'follow_up_date' => 'nullable|date',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'pending';
        DB::table('admission_enquiries')->insert($validated);
        return redirect()->route('admissions.index')->with('success', 'Enquiry added');
    }

    public function updateEnquiry(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'class_id' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:5000',
            'follow_up_date' => 'nullable|date',
        ]);
        DB::table('admission_enquiries')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.index')->with('success', 'Enquiry updated');
    }

    public function destroyEnquiry(int $id)
    {
        DB::table('admission_enquiries')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.index')->with('success', 'Enquiry deleted');
    }

    public function onlinePortal()
    {
        $classes = DB::table('classes')->where('school_id', 1)->orderBy('name')->get();
        return view('admissions.online-portal', compact('classes'));
    }

    public function submitOnlinePortal(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'class_applying_for_id' => 'nullable|integer',
            'address' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $validated['school_id'] = 1;
        $validated['academic_year_id'] = DB::table('academic_years')->where('school_id', 1)->orderBy('id', 'desc')->value('id');
        $validated['applied_date'] = now()->toDateString();
        $validated['status'] = 'pending';
        $validated['form_number'] = 'ONL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('admissions/photos', 'public');
        }
        DB::table('admission_forms')->insert($validated);
        return redirect()->route('admissions.online-portal')->with('success', 'Application submitted! Your form number is ' . $validated['form_number']);
    }

    public function applicationForms(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_forms')->where('admission_forms.school_id', $schoolId)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->select('admission_forms.*', 'classes.name as class_name');
        if ($request->filled('status')) $query->where('admission_forms.status', $request->status);
        if ($request->filled('class_id')) $query->where('admission_forms.class_applying_for_id', $request->class_id);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('admission_forms.applicant_name', 'like', "%{$s}%")->orWhere('admission_forms.form_number', 'like', "%{$s}%")->orWhere('admission_forms.phone', 'like', "%{$s}%");
            });
        }
        $applications = $query->orderBy('admission_forms.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('admissions.application-forms', compact('applications', 'classes'));
    }

    public function storeApplicationForm(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'birth_cert_no' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female,other',
            'class_applying_for_id' => 'nullable|integer',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:1000',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $validated['school_id'] = 1;
        $validated['academic_year_id'] = DB::table('academic_years')->where('school_id', 1)->orderBy('id', 'desc')->value('id');
        $validated['applied_date'] = now()->toDateString();
        $validated['form_number'] = 'ADM-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('admissions/photos', 'public');
        }

        DB::table('admission_forms')->insert($validated);
        return redirect()->route('admissions.application-forms')->with('success', 'Application created');
    }

    public function updateApplicationForm(Request $request, int $id)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'birth_cert_no' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female,other',
            'class_applying_for_id' => 'nullable|integer',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:1000',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('admissions/photos', 'public');
        }

        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.application-forms')->with('success', 'Application updated');
    }

    public function destroyApplicationForm(int $id)
    {
        DB::table('admission_forms')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.application-forms')->with('success', 'Application deleted');
    }

    public function printApplicationForm(int $id)
    {
        $application = DB::table('admission_forms')
            ->where('admission_forms.id', $id)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->leftJoin('academic_years', 'admission_forms.academic_year_id', '=', 'academic_years.id')
            ->select('admission_forms.*', 'classes.name as class_name', 'academic_years.name as academic_year')
            ->first();

        if (!$application) abort(404);

        $school = DB::table('schools')->where('id', 1)->first();

        return view('admissions.print-application', compact('application', 'school'));
    }

    public function admissionEnquiry(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_enquiries')->where('school_id', $schoolId);
        if ($request->filled('status')) $query->where('status', $request->status);
        $enquiries = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admissions.admission-enquiry', compact('enquiries'));
    }

    public function leadManagement(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_enquiries')->where('school_id', $schoolId);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('source')) $query->where('source', $request->source);
        $leads = $query->orderBy('created_at', 'desc')->paginate(15);
        $conversionRate = $leads->total() > 0 ? round(DB::table('admission_enquiries')->where('school_id', $schoolId)->where('status', 'converted')->count() / $leads->total() * 100, 1) : 0;
        return view('admissions.lead-management', compact('leads', 'conversionRate'));
    }

    public function convertLead(int $id)
    {
        DB::table('admission_enquiries')->where('id', $id)->where('school_id', 1)->update(['status' => 'converted']);
        $enquiry = DB::table('admission_enquiries')->where('id', $id)->first();
        DB::table('admission_forms')->insert([
            'school_id' => 1, 'applicant_name' => $enquiry->name, 'phone' => $enquiry->phone,
            'email' => $enquiry->email, 'status' => 'pending', 'applied_date' => now()->toDateString(),
            'form_number' => 'LD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
        ]);
        return redirect()->route('admissions.lead-management')->with('success', 'Lead converted to application');
    }

    public function studentRegistration(Request $request)
    {
        $schoolId = 1;
        $approvedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'approved')->orderBy('applicant_name')->get();
        $students = DB::table('students')->where('school_id', $schoolId)->orderBy('created_at', 'desc')->take(20)->get();
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('admissions.student-registration', compact('approvedForms', 'students', 'classes'));
    }

    public function storeStudentRegistration(Request $request)
    {
        $validated = $request->validate([
            'admission_form_id' => 'required|integer|exists:admission_forms,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'roll_number' => 'nullable|string|max:50',
        ]);

        $form = DB::table('admission_forms')->where('id', $validated['admission_form_id'])->first();
        if (!$form || $form->status !== 'approved') {
            return redirect()->back()->with('error', 'Form must be approved first');
        }

        $maxAdmissionNo = DB::table('students')->where('school_id', 1)->max('admission_no');
        $nextNo = $maxAdmissionNo ? (int)$maxAdmissionNo + 1 : 1001;

        DB::table('students')->insert([
            'school_id' => 1,
            'admission_no' => (string)$nextNo,
            'first_name' => $form->applicant_name,
            'date_of_birth' => $form->date_of_birth,
            'gender' => $form->gender,
            'phone' => $form->phone,
            'email' => $form->email,
            'address' => $form->address,
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'roll_number' => $validated['roll_number'] ?? null,
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        DB::table('admission_forms')->where('id', $validated['admission_form_id'])->update(['status' => 'admitted']);

        return redirect()->route('admissions.student-registration')->with('success', 'Student registered. Admission No: ' . $nextNo);
    }

    public function documentUpload(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_documents')->where('admission_documents.school_id', $schoolId)
            ->join('admission_forms', 'admission_documents.admission_form_id', '=', 'admission_forms.id')
            ->select('admission_documents.*', 'admission_forms.applicant_name', 'admission_forms.form_number');
        if ($request->filled('form_id')) $query->where('admission_documents.admission_form_id', $request->form_id);
        if ($request->filled('doc_type')) $query->where('admission_documents.document_type', $request->doc_type);
        $documents = $query->orderBy('admission_documents.created_at', 'desc')->paginate(15);
        $forms = DB::table('admission_forms')->where('school_id', $schoolId)->orderBy('applicant_name')->get();
        return view('admissions.document-upload', compact('documents', 'forms'));
    }

    public function storeDocument(Request $request)
    {
        $validated = $request->validate([
            'admission_form_id' => 'required|integer|exists:admission_forms,id',
            'document_type' => 'required|string|max:100',
            'document_file' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
        ]);
        $validated['school_id'] = 1;
        $validated['file_path'] = $request->file('document_file')->store('admissions/documents', 'public');
        $validated['original_name'] = $request->file('document_file')->getClientOriginalName();
        DB::table('admission_documents')->insert($validated);
        return redirect()->route('admissions.document-upload')->with('success', 'Document uploaded');
    }

    public function deleteDocument(int $id)
    {
        DB::table('admission_documents')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Document deleted');
    }

    public function admissionWorkflow()
    {
        $schoolId = 1;
        $stages = [
            ['name' => 'Enquiry', 'icon' => 'fa-question-circle', 'color' => 'info', 'count' => DB::table('admission_enquiries')->where('school_id', $schoolId)->count()],
            ['name' => 'Application', 'icon' => 'fa-file-alt', 'color' => 'primary', 'count' => DB::table('admission_forms')->where('school_id', $schoolId)->count()],
            ['name' => 'Document Verification', 'icon' => 'fa-check-double', 'color' => 'warning', 'count' => DB::table('admission_documents')->where('school_id', $schoolId)->count()],
            ['name' => 'Entrance Exam', 'icon' => 'fa-pencil-alt', 'color' => 'secondary', 'count' => DB::table('entrance_exam_results')->count()],
            ['name' => 'Interview', 'icon' => 'fa-users', 'color' => 'info', 'count' => DB::table('admission_forms')->where('school_id', $schoolId)->whereNotNull('interview_date')->count()],
            ['name' => 'Approved', 'icon' => 'fa-check-circle', 'color' => 'success', 'count' => DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'approved')->count()],
            ['name' => 'Fee Collection', 'icon' => 'fa-money-bill-wave', 'color' => 'warning', 'count' => DB::table('fee_collections')->where('school_id', $schoolId)->count()],
            ['name' => 'Student Registered', 'icon' => 'fa-user-graduate', 'color' => 'success', 'count' => DB::table('students')->where('school_id', $schoolId)->count()],
        ];
        return view('admissions.admission-workflow', compact('stages'));
    }

    public function admissionApproval(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_forms')->where('admission_forms.school_id', $schoolId)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->select('admission_forms.*', 'classes.name as class_name')
            ->whereIn('admission_forms.status', ['pending', 'waiting']);
        if ($request->filled('class_id')) $query->where('admission_forms.class_applying_for_id', $request->class_id);
        $applications = $query->orderBy('admission_forms.created_at', 'desc')->paginate(20);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('admissions.admission-approval', compact('applications', 'classes'));
    }

    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->back()->with('error', 'No applications selected');
        DB::table('admission_forms')->whereIn('id', $ids)->where('school_id', 1)->update(['status' => 'approved']);
        return redirect()->route('admissions.admission-approval')->with('success', count($ids) . ' applications approved');
    }

    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return redirect()->back()->with('error', 'No applications selected');
        DB::table('admission_forms')->whereIn('id', $ids)->where('school_id', 1)->update(['status' => 'rejected', 'remarks' => 'Bulk reject']);
        return redirect()->route('admissions.admission-approval')->with('success', count($ids) . ' applications rejected');
    }

    public function meritListGeneration(Request $request)
    {
        $schoolId = 1;
        $examResults = DB::table('entrance_exam_results')
            ->join('admission_forms', 'entrance_exam_results.admission_form_id', '=', 'admission_forms.id')
            ->join('entrance_exams', 'entrance_exam_results.entrance_exam_id', '=', 'entrance_exams.id')
            ->select('entrance_exam_results.*', 'admission_forms.applicant_name', 'admission_forms.form_number', 'admission_forms.class_applying_for_id', 'entrance_exams.title as exam_title')
            ->orderBy('entrance_exam_results.percentage', 'desc')
            ->paginate(20);
        $meritLists = DB::table('merit_lists')->where('merit_lists.school_id', $schoolId)
            ->leftJoin('classes', 'merit_lists.class_id', '=', 'classes.id')
            ->select('merit_lists.*', 'classes.name as class_name')->orderBy('created_at', 'desc')->get();
        return view('admissions.merit-list-generation', compact('examResults', 'meritLists'));
    }

    public function generateMeritList(Request $request)
    {
        $validated = $request->validate(['title' => 'required|string|max:255', 'class_id' => 'nullable|integer',]);
        $schoolId = 1;
        $topResults = DB::table('entrance_exam_results')
            ->join('admission_forms', 'entrance_exam_results.admission_form_id', '=', 'admission_forms.id')
            ->where('admission_forms.school_id', $schoolId)
            ->when($validated['class_id'], fn($q) => $q->where('admission_forms.class_applying_for_id', $validated['class_id']))
            ->select('entrance_exam_results.admission_form_id', 'entrance_exam_results.percentage')
            ->orderBy('entrance_exam_results.percentage', 'desc')
            ->take(50)->get();

        $ranks = $topResults->map(fn($r, $i) => ['form_id' => $r->admission_form_id, 'rank' => $i + 1, 'score' => $r->percentage])->toArray();

        DB::table('merit_lists')->insert([
            'school_id' => $schoolId, 'title' => $validated['title'], 'class_id' => $validated['class_id'] ?? null,
            'ranks' => json_encode($ranks), 'generated_by' => auth()->id(), 'generated_at' => now(),
            'generated_date' => now()->toDateString(), 'status' => 'draft',
        ]);
        return redirect()->route('admissions.merit-list-generation')->with('success', 'Merit list generated with ' . count($ranks) . ' entries');
    }

    public function interviewScheduling(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('admission_forms')->where('admission_forms.school_id', $schoolId)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->select('admission_forms.*', 'classes.name as class_name')
            ->whereIn('admission_forms.status', ['pending', 'approved']);
        if ($request->filled('status')) $query->where('admission_forms.status', $request->status);
        $applications = $query->orderBy('admission_forms.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('admissions.interview-scheduling', compact('applications', 'classes'));
    }

    public function scheduleInterview(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:admission_forms,id',
            'interview_date' => 'required|date',
            'interview_time' => 'required',
        ]);
        DB::table('admission_forms')->where('id', $request->form_id)->where('school_id', 1)->update([
            'interview_date' => $request->interview_date, 'interview_time' => $request->interview_time,
        ]);
        return redirect()->route('admissions.interview-scheduling')->with('success', 'Interview scheduled');
    }

    public function admissionFeeCollection(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('fee_collections')->where('fee_collections.school_id', $schoolId)
            ->join('students', 'fee_collections.student_id', '=', 'students.id')
            ->select('fee_collections.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->where('fee_collections.payment_date', '>=', now()->subMonths(3));
        if ($request->filled('payment_method')) $query->where('fee_collections.payment_method', $request->payment_method);
        $collections = $query->orderBy('fee_collections.payment_date', 'desc')->paginate(15);
        $totalAdmissionFees = DB::table('fee_collections')->where('school_id', $schoolId)->sum('paid_amount');
        $students = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();
        $feeStructures = DB::table('fee_structures')->where('school_id', $schoolId)->orderBy('name')->get();
        return view('admissions.admission-fee-collection', compact('collections', 'totalAdmissionFees', 'students', 'feeStructures'));
    }

    public function storeAdmissionFee(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'fee_structure_id' => 'required|integer|exists:fee_structures,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'payment_date' => 'required|date',
        ]);
        $validated['school_id'] = 1;
        $validated['paid_amount'] = $validated['amount'];
        $validated['total_amount'] = $validated['amount'];
        $validated['status'] = 'paid';
        $validated['receipt_number'] = 'RCP-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        DB::table('fee_collections')->insert($validated);
        return redirect()->route('admissions.admission-fee-collection')->with('success', 'Fee collected. Receipt: ' . $validated['receipt_number']);
    }

    public function studentIdGeneration()
    {
        $schoolId = 1;
        $students = DB::table('students')->where('students.school_id', $schoolId)
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*', 'classes.name as class_name')
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('admissions.student-id-generation', compact('students'));
    }

    public function generateStudentId(int $id)
    {
        $student = DB::table('students')->where('id', $id)->where('school_id', 1)->first();
        if (!$student) return redirect()->back()->with('error', 'Student not found');
        $prefix = 'STU-' . now()->format('Y');
        $admissionNo = $student->admission_no ?? $prefix . str_pad($id, 5, '0', STR_PAD_LEFT);
        if (!$student->admission_no) {
            DB::table('students')->where('id', $id)->update(['admission_no' => $admissionNo]);
        }
        return redirect()->back()->with('success', 'Student ID: ' . $admissionNo);
    }

    public function parentRegistration(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('parents')->where('parents.school_id', $schoolId)
            ->leftJoin('student_parents', 'parents.id', '=', 'student_parents.parent_id')
            ->leftJoin('students', 'student_parents.student_id', '=', 'students.id')
            ->select('parents.*', 'students.first_name as student_name', 'students.admission_no', 'student_parents.relationship');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) { $q->where('parents.first_name', 'like', "%{$s}%")->orWhere('parents.phone', 'like', "%{$s}%"); });
        }
        $parents = $query->orderBy('parents.created_at', 'desc')->paginate(15);
        $students = DB::table('students')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();
        return view('admissions.parent-registration', compact('parents', 'students'));
    }

    public function storeParent(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:1000',
            'relationship' => 'nullable|string|max:50',
            'student_id' => 'nullable|integer|exists:students,id',
        ]);
        $validated['school_id'] = 1;
        $parentId = DB::table('parents')->insertGetId($validated);
        if ($request->filled('student_id')) {
            DB::table('student_parents')->insert([
                'student_id' => $request->student_id, 'parent_id' => $parentId,
                'relationship' => $request->relationship ?? 'father',
            ]);
        }
        return redirect()->route('admissions.parent-registration')->with('success', 'Parent registered');
    }

    public function updateParent(Request $request, int $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:1000',
        ]);
        DB::table('parents')->where('id', $id)->where('school_id', 1)->update($validated);
        return redirect()->route('admissions.parent-registration')->with('success', 'Parent updated');
    }

    public function destroyParent(int $id)
    {
        DB::table('parents')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('admissions.parent-registration')->with('success', 'Parent deleted');
    }

    public function admissionReports()
    {
        $schoolId = 1;
        $totalEnquiries = DB::table('admission_enquiries')->where('school_id', $schoolId)->count();
        $totalForms = DB::table('admission_forms')->where('school_id', $schoolId)->count();
        $approvedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'approved')->count();
        $admittedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'admitted')->count();
        $rejectedForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'rejected')->count();
        $pendingForms = DB::table('admission_forms')->where('school_id', $schoolId)->where('status', 'pending')->count();
        $statusStats = DB::table('admission_forms')->where('school_id', $schoolId)->select('status', DB::raw('count(*) as total'))->groupBy('status')->get();
        $monthlyForms = DB::table('admission_forms')->where('school_id', $schoolId)->whereYear('created_at', now()->year)->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month')->get();
        $classStats = DB::table('admission_forms')->where('admission_forms.school_id', $schoolId)->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')->select('classes.name', DB::raw('count(*) as total'))->groupBy('classes.name')->get();
        $admissionFees = DB::table('fee_collections')->where('school_id', $schoolId)->sum('paid_amount');
        return view('admissions.admission-reports', compact('totalEnquiries', 'totalForms', 'approvedForms', 'admittedForms', 'rejectedForms', 'pendingForms', 'statusStats', 'monthlyForms', 'classStats', 'admissionFees'));
    }

    public function submitPublicApplicationForm(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'birth_cert_no' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female,other',
            'class_applying_for_id' => 'nullable|integer',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:1000',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = 1;
        $validated['academic_year_id'] = DB::table('academic_years')->where('school_id', 1)->orderBy('id', 'desc')->value('id');
        $validated['status'] = 'pending';
        $validated['applied_date'] = now()->toDateString();
        $validated['form_number'] = 'PUB-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('admissions/photos', 'public');
        }

        $id = DB::table('admission_forms')->insertGetId($validated);
        return redirect()->route('public.application-form.submitted', $id)->with('success', 'Application submitted! Your form number is ' . $validated['form_number']);
    }

    public function submittedAction(int $id)
    {
        $application = DB::table('admission_forms')
            ->where('admission_forms.id', $id)
            ->leftJoin('classes', 'admission_forms.class_applying_for_id', '=', 'classes.id')
            ->leftJoin('academic_years', 'admission_forms.academic_year_id', '=', 'academic_years.id')
            ->select('admission_forms.*', 'classes.name as class_name', 'academic_years.name as academic_year')
            ->first();

        if (!$application) abort(404);

        return view('admissions.submitted-action', compact('application'));
    }
}

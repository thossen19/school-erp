<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    // ============ Student Health Records ============

    public function studentHealthRecords(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('health_records')
            ->leftJoin('students', 'health_records.student_id', '=', 'students.id')
            ->where('health_records.school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('students.admission_no', 'like', "%{$v}%");
            }))
            ->select('health_records.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('health_records.checkup_date', 'desc')
            ->paginate(20);

        $students = DB::table('students')->where('school_id', $schoolId)->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'admission_no']);

        return view('health.student-records', compact('records', 'students'));
    }

    public function storeHealthRecord(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string|max:20',
            'vision_left' => 'nullable|string|max:20',
            'vision_right' => 'nullable|string|max:20',
            'dental_health' => 'nullable|string',
            'checkup_date' => 'required|date',
            'notes' => 'nullable|string',
            'conducted_by' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = session('school_id', 1);

        DB::table('health_records')->insert($validated);

        return redirect()->route('health.student-records')->with('success', 'Health record created successfully');
    }

    public function updateHealthRecord(Request $request, int $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string|max:20',
            'vision_left' => 'nullable|string|max:20',
            'vision_right' => 'nullable|string|max:20',
            'dental_health' => 'nullable|string',
            'checkup_date' => 'required|date',
            'notes' => 'nullable|string',
            'conducted_by' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:255',
        ]);

        DB::table('health_records')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('health.student-records')->with('success', 'Health record updated');
    }

    public function deleteHealthRecord(int $id)
    {
        DB::table('health_records')->where('id', $id)->delete();
        return redirect()->route('health.student-records')->with('success', 'Health record deleted');
    }

    // ============ Vaccination Records ============

    public function vaccinations(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('vaccination_records')
            ->leftJoin('students', 'vaccination_records.student_id', '=', 'students.id')
            ->where('vaccination_records.school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('vaccination_records.vaccine_name', 'like', "%{$v}%");
            }))
            ->when($request->status === 'upcoming', fn($q) => $q->where('vaccination_records.next_due_date', '>=', now()))
            ->select('vaccination_records.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('vaccination_records.vaccination_date', 'desc')
            ->paginate(20);

        return view('health.vaccinations', compact('records'));
    }

    public function storeVaccination(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'vaccine_name' => 'required|string|max:255',
            'dose_number' => 'nullable|integer|min:1',
            'vaccination_date' => 'required|date',
            'next_due_date' => 'nullable|date',
            'administered_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'batch_number' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = session('school_id', 1);
        $validated['dose_number'] ??= 1;
        $validated['batch_number'] ??= null;

        DB::table('vaccination_records')->insert($validated);

        return redirect()->route('health.vaccinations')->with('success', 'Vaccination record created');
    }

    public function updateVaccination(Request $request, int $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'vaccine_name' => 'required|string|max:255',
            'dose_number' => 'nullable|integer|min:1',
            'vaccination_date' => 'required|date',
            'next_due_date' => 'nullable|date',
            'administered_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'batch_number' => 'nullable|string|max:255',
        ]);

        $validated['dose_number'] ??= 1;

        DB::table('vaccination_records')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('health.vaccinations')->with('success', 'Vaccination record updated');
    }

    public function deleteVaccination(int $id)
    {
        DB::table('vaccination_records')->where('id', $id)->delete();
        return redirect()->route('health.vaccinations')->with('success', 'Vaccination record deleted');
    }

    // ============ Medical History (student_medical_records) ============

    public function medicalHistory(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('student_medical_records')
            ->leftJoin('students', 'student_medical_records.student_id', '=', 'students.id')
            ->where('student_medical_records.school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('students.admission_no', 'like', "%{$v}%");
            }))
            ->when($request->blood_group, fn($q, $v) => $q->where('student_medical_records.blood_group', $v))
            ->select('student_medical_records.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('students.first_name')
            ->paginate(20);

        return view('health.medical-history', compact('records'));
    }

    public function updateMedicalHistory(Request $request, int $id)
    {
        $validated = $request->validate([
            'blood_group' => 'nullable|string|max:10',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'immunization_records' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'primary_care_physician' => 'nullable|string|max:100',
            'physician_phone' => 'nullable|string|max:20',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_policy_no' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        $jsonFields = ['allergies', 'medical_conditions', 'medications', 'immunization_records'];
        foreach ($jsonFields as $field) {
            if (isset($validated[$field]) && is_string($validated[$field])) {
                $validated[$field] = json_encode(array_filter(array_map('trim', explode(',', $validated[$field]))));
            }
        }

        DB::table('student_medical_records')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('health.medical-history')->with('success', 'Medical history updated');
    }

    // ============ Health Checkups ============

    public function checkups(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('health_records')
            ->leftJoin('students', 'health_records.student_id', '=', 'students.id')
            ->where('health_records.school_id', $schoolId)
            ->when($request->student_id, fn($q, $v) => $q->where('health_records.student_id', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('health_records.checkup_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('health_records.checkup_date', '<=', $v))
            ->select('health_records.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('health_records.checkup_date', 'desc')
            ->paginate(20);

        $students = DB::table('students')->where('school_id', $schoolId)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        return view('health.checkups', compact('records', 'students'));
    }

    // ============ Medicine Tracking ============

    public function medicines(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('medicines')
            ->leftJoin('students', 'medicines.student_id', '=', 'students.id')
            ->where('medicines.school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('medicines.medicine_name', 'like', "%{$v}%");
            }))
            ->select('medicines.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('medicines.start_date', 'desc')
            ->paginate(20);

        return view('health.medicines', compact('records'));
    }

    public function storeMedicine(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'prescribed_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'strength' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = session('school_id', 1);

        DB::table('medicines')->insert($validated);

        return redirect()->route('health.medicines')->with('success', 'Medicine record created');
    }

    public function updateMedicine(Request $request, int $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'prescribed_by' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'strength' => 'nullable|string|max:255',
        ]);

        DB::table('medicines')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('health.medicines')->with('success', 'Medicine record updated');
    }

    public function deleteMedicine(int $id)
    {
        DB::table('medicines')->where('id', $id)->delete();
        return redirect()->route('health.medicines')->with('success', 'Medicine record deleted');
    }

    // ============ Emergency Contacts ============

    public function emergencyContacts(Request $request)
    {
        $schoolId = session('school_id', 1);

        $records = DB::table('emergency_contacts')
            ->leftJoin('students', 'emergency_contacts.student_id', '=', 'students.id')
            ->where('emergency_contacts.school_id', $schoolId)
            ->when($request->search, fn($q, $v) => $q->where(function($q) use ($v) {
                $q->where('students.first_name', 'like', "%{$v}%")
                  ->orWhere('students.last_name', 'like', "%{$v}%")
                  ->orWhere('emergency_contacts.contact_name', 'like', "%{$v}%");
            }))
            ->select('emergency_contacts.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('emergency_contacts.contact_name')
            ->paginate(20);

        return view('health.emergency-contacts', compact('records'));
    }

    public function storeEmergencyContact(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'contact_name' => 'required|string|max:255',
            'relation' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['school_id'] = session('school_id', 1);
        $validated['is_primary'] = $request->boolean('is_primary');

        if ($validated['is_primary']) {
            DB::table('emergency_contacts')->where('student_id', $validated['student_id'])->update(['is_primary' => false]);
        }

        DB::table('emergency_contacts')->insert($validated);

        return redirect()->route('health.emergency-contacts')->with('success', 'Emergency contact added');
    }

    public function updateEmergencyContact(Request $request, int $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'contact_name' => 'required|string|max:255',
            'relation' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->boolean('is_primary');

        if ($validated['is_primary']) {
            DB::table('emergency_contacts')->where('student_id', $validated['student_id'])->where('id', '!=', $id)->update(['is_primary' => false]);
        }

        DB::table('emergency_contacts')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return redirect()->route('health.emergency-contacts')->with('success', 'Emergency contact updated');
    }

    public function deleteEmergencyContact(int $id)
    {
        DB::table('emergency_contacts')->where('id', $id)->delete();
        return redirect()->route('health.emergency-contacts')->with('success', 'Emergency contact deleted');
    }

    // ============ Health Reports ============

    public function reports()
    {
        $schoolId = session('school_id', 1);

        $totalRecords = DB::table('health_records')->where('school_id', $schoolId)->count();
        $totalVaccinations = DB::table('vaccination_records')->where('school_id', $schoolId)->count();
        $totalMedicalRecords = DB::table('student_medical_records')->where('school_id', $schoolId)->count();
        $totalMedicines = DB::table('medicines')->where('school_id', $schoolId)->count();
        $totalContacts = DB::table('emergency_contacts')->where('school_id', $schoolId)->count();

        $upcomingVaccinations = DB::table('vaccination_records')
            ->leftJoin('students', 'vaccination_records.student_id', '=', 'students.id')
            ->where('vaccination_records.school_id', $schoolId)
            ->where('vaccination_records.next_due_date', '>=', now())
            ->whereNotNull('vaccination_records.next_due_date')
            ->select('vaccination_records.*', 'students.first_name', 'students.last_name')
            ->orderBy('vaccination_records.next_due_date')
            ->limit(10)
            ->get();

        $recentCheckups = DB::table('health_records')
            ->leftJoin('students', 'health_records.student_id', '=', 'students.id')
            ->where('health_records.school_id', $schoolId)
            ->select('health_records.*', 'students.first_name', 'students.last_name')
            ->orderBy('health_records.checkup_date', 'desc')
            ->limit(10)
            ->get();

        $activeMedications = DB::table('medicines')
            ->leftJoin('students', 'medicines.student_id', '=', 'students.id')
            ->where('medicines.school_id', $schoolId)
            ->where(function($q) {
                $q->whereNull('medicines.end_date')->orWhere('medicines.end_date', '>=', now());
            })
            ->select('medicines.*', 'students.first_name', 'students.last_name')
            ->orderBy('medicines.start_date', 'desc')
            ->limit(10)
            ->get();

        $bloodGroupStats = DB::table('student_medical_records')
            ->where('school_id', $schoolId)
            ->whereNotNull('blood_group')
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->orderBy('total', 'desc')
            ->get();

        return view('health.reports', compact(
            'totalRecords', 'totalVaccinations', 'totalMedicalRecords',
            'totalMedicines', 'totalContacts', 'upcomingVaccinations',
            'recentCheckups', 'activeMedications', 'bloodGroupStats'
        ));
    }
}

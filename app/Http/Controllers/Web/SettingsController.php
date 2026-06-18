<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function school()
    {
        $school = DB::table('schools')->where('id', 1)->first();
        return view('settings.school', compact('school'));
    }

    public function updateSchool(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'short_code' => 'nullable|string|max:20',
            'code' => 'sometimes|string|max:50',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'e_tin' => 'nullable|string|max:50',
            'registration_no' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('school/logo', 'public');
        }

        DB::table('schools')->where('id', 1)->update($validated);
        return redirect()->route('settings.school')->with('success', 'School settings updated');
    }

    public function academicYears()
    {
        $academicYears = DB::table('academic_years')->where('school_id', 1)->orderBy('start_date', 'desc')->paginate(10);
        return view('settings.academic-years', compact('academicYears'));
    }

    public function storeAcademicYear(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        $validated['school_id'] = 1;

        if ($validated['is_current'] ?? false) {
            DB::table('academic_years')->where('school_id', 1)->where('is_current', true)->update(['is_current' => false]);
        }

        DB::table('academic_years')->insert($validated);
        return redirect()->route('settings.academic-years')->with('success', 'Academic year created');
    }

    public function general()
    {
        $school = DB::table('schools')->where('id', 1)->first();
        $academicYears = DB::table('academic_years')->where('school_id', 1)->orderBy('start_date', 'desc')->get();
        $profile = DB::table('user_profiles')->where('user_id', auth()->id())->first();
        return view('settings.general', compact('school', 'academicYears', 'profile'));
    }

    public function updateGeneral(Request $request)
    {
        $schoolData = $request->validate([
            'name' => 'required|string|max:255',
            'e_tin' => 'nullable|string|max:50',
            'registration_no' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,jpg|max:2048']);
            $schoolData['logo'] = $request->file('logo')->store('school/logo', 'public');
        }

        DB::table('schools')->where('id', 1)->update($schoolData);

        $prefData = $request->validate([
            'theme' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
        ]);

        $profile = DB::table('user_profiles')->where('user_id', auth()->id())->first();
        if ($profile) {
            DB::table('user_profiles')->where('user_id', auth()->id())->update($prefData);
        }

        if ($request->filled('fiscal_year_name')) {
            $fyValidated = $request->validate([
                'fiscal_year_name' => 'required|string|max:100',
                'fiscal_year_start' => 'required|date',
                'fiscal_year_end' => 'required|date|after:fiscal_year_start',
                'fiscal_year_current' => 'boolean',
            ]);

            $fyData = [
                'school_id' => 1,
                'name' => $fyValidated['fiscal_year_name'],
                'start_date' => $fyValidated['fiscal_year_start'],
                'end_date' => $fyValidated['fiscal_year_end'],
                'is_current' => $request->boolean('fiscal_year_current'),
            ];

            if ($fyData['is_current']) {
                DB::table('academic_years')->where('school_id', 1)->where('is_current', true)->update(['is_current' => false]);
            }

            DB::table('academic_years')->insert($fyData);
        }

        return redirect()->route('settings.general')->with('success', 'Settings updated');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumniController extends Controller
{
    private function schoolId() { return 1; }

    public function index(Request $request)
    {
        $q = DB::table('alumni')->where('school_id', $this->schoolId());
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->graduation_year) $q->where('graduation_year', $request->graduation_year);
        if ($request->is_verified !== null && $request->is_verified !== '') $q->where('is_verified', $request->is_verified);
        $alumni = $q->orderBy('graduation_year', 'desc')->orderBy('first_name')->paginate(20);
        $years = DB::table('alumni')->where('school_id', $this->schoolId())->distinct()->orderBy('graduation_year', 'desc')->pluck('graduation_year');
        return view('alumni.index', compact('alumni', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:alumni,email', 'phone' => 'nullable|string|max:20',
            'graduation_year' => 'required|integer|min:1950|max:2100', 'current_occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255', 'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);
        DB::table('alumni')->insert([
            'school_id' => $this->schoolId(), 'first_name' => $request->first_name, 'last_name' => $request->last_name,
            'email' => $request->email, 'phone' => $request->phone, 'graduation_year' => $request->graduation_year,
            'current_occupation' => $request->current_occupation, 'company' => $request->company,
            'designation' => $request->designation, 'address' => $request->address,
            'is_verified' => $request->boolean('is_verified'), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.index')->with('success', 'Alumni record created');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'first_name' => 'sometimes|string|max:100', 'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255|unique:alumni,email,'.$id, 'phone' => 'nullable|string|max:20',
            'graduation_year' => 'sometimes|integer|min:1950|max:2100', 'current_occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255', 'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);
        DB::table('alumni')->where('id', $id)->update([
            'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email,
            'phone' => $request->phone, 'graduation_year' => $request->graduation_year,
            'current_occupation' => $request->current_occupation, 'company' => $request->company,
            'designation' => $request->designation, 'address' => $request->address,
            'is_verified' => $request->boolean('is_verified'), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.index')->with('success', 'Alumni record updated');
    }

    public function destroy(int $id)
    {
        DB::table('alumni')->where('id', $id)->delete();
        return redirect()->route('alumni.index')->with('success', 'Alumni record deleted');
    }

    public function verify(int $id)
    {
        DB::table('alumni')->where('id', $id)->update(['is_verified' => true, 'updated_at' => now()]);
        return redirect()->route('alumni.index')->with('success', 'Alumni verified');
    }

    // --- Alumni Portal (dashboard) ---
    public function portal()
    {
        $sid = $this->schoolId();
        $total = DB::table('alumni')->where('school_id', $sid)->count();
        $verified = DB::table('alumni')->where('school_id', $sid)->where('is_verified', true)->count();
        $upcomingEvents = DB::table('alumni_events')->where('school_id', $sid)->where('date', '>=', now()->toDateString())->where('status', true)->orderBy('date')->limit(5)->get();
        $totalDonations = DB::table('alumni_donations')->where('school_id', $sid)->sum('amount');
        $activeJobs = DB::table('alumni_jobs')->where('school_id', $sid)->where('status', 'active')->count();
        return view('alumni.portal', compact('total', 'verified', 'upcomingEvents', 'totalDonations', 'activeJobs'));
    }

    // --- Events ---
    public function events(Request $request)
    {
        $q = DB::table('alumni_events')->where('school_id', $this->schoolId());
        if ($search = $request->search) $q->where('title', 'like', "%{$search}%");
        if ($request->status !== null && $request->status !== '') $q->where('status', $request->status);
        $events = $q->orderBy('date', 'desc')->paginate(20);
        return view('alumni.events', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string', 'date' => 'required|date', 'venue' => 'nullable|string|max:255']);
        DB::table('alumni_events')->insert([
            'school_id' => $this->schoolId(), 'title' => $request->title, 'description' => $request->description,
            'date' => $request->date, 'venue' => $request->venue, 'status' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.events')->with('success', 'Event created');
    }

    public function updateEvent(Request $request, int $id)
    {
        $request->validate(['title' => 'sometimes|string|max:255', 'description' => 'nullable|string', 'date' => 'sometimes|date', 'venue' => 'nullable|string|max:255', 'status' => 'boolean']);
        $upd = [];
        foreach (['title','description','date','venue'] as $f) { if ($request->has($f)) $upd[$f] = $request->$f; }
        if ($request->has('status')) $upd['status'] = $request->boolean('status');
        $upd['updated_at'] = now();
        DB::table('alumni_events')->where('id', $id)->update($upd);
        return redirect()->route('alumni.events')->with('success', 'Event updated');
    }

    public function deleteEvent(int $id)
    {
        DB::table('alumni_events')->where('id', $id)->delete();
        return redirect()->route('alumni.events')->with('success', 'Event deleted');
    }

    // --- Donations ---
    public function donations(Request $request)
    {
        $q = DB::table('alumni_donations as d')->join('alumni as a', 'd.alumni_id', '=', 'a.id')
            ->where('d.school_id', $this->schoolId())->select('d.*', 'a.first_name', 'a.last_name', 'a.email as alumnus_email');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('a.first_name', 'like', "%{$search}%")
                   ->orWhere('a.last_name', 'like', "%{$search}%")
                   ->orWhere('d.purpose', 'like', "%{$search}%");
            });
        }
        if ($request->payment_mode) $q->where('d.payment_mode', $request->payment_mode);
        $donations = $q->orderBy('d.donation_date', 'desc')->paginate(20);
        $modes = DB::table('alumni_donations')->where('school_id', $this->schoolId())->distinct()->pluck('payment_mode');
        $alumniList = DB::table('alumni')->where('school_id', $this->schoolId())->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('alumni.donations', compact('donations', 'modes', 'alumniList'));
    }

    public function storeDonation(Request $request)
    {
        $request->validate([
            'alumni_id' => 'required|integer|exists:alumni,id', 'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date', 'purpose' => 'nullable|string|max:255',
            'payment_mode' => 'nullable|string|max:50', 'remarks' => 'nullable|string',
        ]);
        DB::table('alumni_donations')->insert([
            'school_id' => $this->schoolId(), 'alumni_id' => $request->alumni_id, 'amount' => $request->amount,
            'donation_date' => $request->donation_date, 'purpose' => $request->purpose,
            'payment_mode' => $request->payment_mode, 'remarks' => $request->remarks,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.donations')->with('success', 'Donation recorded');
    }

    public function deleteDonation(int $id)
    {
        DB::table('alumni_donations')->where('id', $id)->delete();
        return redirect()->route('alumni.donations')->with('success', 'Donation deleted');
    }

    // --- Job Board ---
    public function jobs(Request $request)
    {
        $q = DB::table('alumni_jobs')->where('school_id', $this->schoolId());
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('title', 'like', "%{$search}%")
                   ->orWhere('company', 'like', "%{$search}%")
                   ->orWhere('location', 'like', "%{$search}%");
            });
        }
        if ($request->job_type) $q->where('job_type', $request->job_type);
        if ($request->status) $q->where('status', $request->status);
        $jobs = $q->orderBy('created_at', 'desc')->paginate(20);
        return view('alumni.jobs', compact('jobs'));
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', 'company' => 'required|string|max:255',
            'description' => 'nullable|string', 'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full_time,part_time,contract,internship',
            'salary_range' => 'nullable|string|max:100', 'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email|max:255',
        ]);
        DB::table('alumni_jobs')->insert([
            'school_id' => $this->schoolId(), 'title' => $request->title, 'company' => $request->company,
            'description' => $request->description, 'location' => $request->location,
            'job_type' => $request->job_type ?? 'full_time', 'salary_range' => $request->salary_range,
            'application_deadline' => $request->application_deadline, 'contact_email' => $request->contact_email,
            'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.jobs')->with('success', 'Job posted');
    }

    public function updateJob(Request $request, int $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255', 'company' => 'sometimes|string|max:255',
            'description' => 'nullable|string', 'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full_time,part_time,contract,internship',
            'salary_range' => 'nullable|string|max:100', 'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email|max:255', 'status' => 'nullable|string|in:active,closed',
        ]);
        $upd = [];
        foreach (['title','company','description','location','job_type','salary_range','application_deadline','contact_email','status'] as $f) {
            if ($request->has($f)) $upd[$f] = $request->$f;
        }
        $upd['updated_at'] = now();
        DB::table('alumni_jobs')->where('id', $id)->update($upd);
        return redirect()->route('alumni.jobs')->with('success', 'Job updated');
    }

    public function deleteJob(int $id)
    {
        DB::table('alumni_jobs')->where('id', $id)->delete();
        return redirect()->route('alumni.jobs')->with('success', 'Job deleted');
    }

    // --- Networking Platform ---
    public function networking(Request $request)
    {
        $q = DB::table('alumni_networking as n')->join('alumni as a', 'n.alumni_id', '=', 'a.id')
            ->where('n.school_id', $this->schoolId())
            ->select('n.*', 'a.first_name', 'a.last_name', 'a.email', 'a.graduation_year', 'a.current_occupation', 'a.company', 'a.designation');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('a.first_name', 'like', "%{$search}%")
                   ->orWhere('a.last_name', 'like', "%{$search}%")
                   ->orWhere('n.industry', 'like', "%{$search}%");
            });
        }
        if ($request->industry) $q->where('n.industry', $request->industry);
        if ($request->available_for_mentorship !== null && $request->available_for_mentorship !== '') $q->where('n.available_for_mentorship', $request->available_for_mentorship);
        $profiles = $q->orderBy('a.first_name')->paginate(20);
        $industries = DB::table('alumni_networking')->where('school_id', $this->schoolId())->whereNotNull('industry')->distinct()->pluck('industry');
        $alumniList = DB::table('alumni')->where('school_id', $this->schoolId())->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('alumni.networking', compact('profiles', 'industries', 'alumniList'));
    }

    public function storeNetworkProfile(Request $request)
    {
        $request->validate([
            'alumni_id' => 'required|integer|unique:alumni_networking,alumni_id', 'industry' => 'nullable|string|max:255',
            'skills' => 'nullable|string', 'interests' => 'nullable|string',
            'bio' => 'nullable|string', 'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255', 'available_for_mentorship' => 'boolean',
        ]);
        DB::table('alumni_networking')->insert([
            'school_id' => $this->schoolId(), 'alumni_id' => $request->alumni_id, 'industry' => $request->industry,
            'skills' => $request->skills, 'interests' => $request->interests, 'bio' => $request->bio,
            'linkedin_url' => $request->linkedin_url, 'portfolio_url' => $request->portfolio_url,
            'available_for_mentorship' => $request->boolean('available_for_mentorship'),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('alumni.networking')->with('success', 'Network profile created');
    }

    public function updateNetworkProfile(Request $request, int $id)
    {
        $request->validate([
            'industry' => 'nullable|string|max:255', 'skills' => 'nullable|string', 'interests' => 'nullable|string',
            'bio' => 'nullable|string', 'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255', 'available_for_mentorship' => 'boolean',
        ]);
        $upd = [];
        foreach (['industry','skills','interests','bio','linkedin_url','portfolio_url'] as $f) { if ($request->has($f)) $upd[$f] = $request->$f; }
        if ($request->has('available_for_mentorship')) $upd['available_for_mentorship'] = $request->boolean('available_for_mentorship');
        $upd['updated_at'] = now();
        DB::table('alumni_networking')->where('id', $id)->update($upd);
        return redirect()->route('alumni.networking')->with('success', 'Network profile updated');
    }

    public function deleteNetworkProfile(int $id)
    {
        DB::table('alumni_networking')->where('id', $id)->delete();
        return redirect()->route('alumni.networking')->with('success', 'Network profile deleted');
    }

    // --- Alumni Reports ---
    public function reports()
    {
        $sid = $this->schoolId();
        $totalAlumni = DB::table('alumni')->where('school_id', $sid)->count();
        $verifiedAlumni = DB::table('alumni')->where('school_id', $sid)->where('is_verified', true)->count();
        $totalDonations = DB::table('alumni_donations')->where('school_id', $sid)->sum('amount');
        $activeJobs = DB::table('alumni_jobs')->where('school_id', $sid)->where('status', 'active')->count();
        $upcomingEvents = DB::table('alumni_events')->where('school_id', $sid)->where('date', '>=', now()->toDateString())->where('status', true)->count();

        $yearlyBreakdown = DB::table('alumni')->where('school_id', $sid)->selectRaw('graduation_year, count(*) as total')->groupBy('graduation_year')->orderBy('graduation_year', 'desc')->get();
        $recentAlumni = DB::table('alumni')->where('school_id', $sid)->orderBy('created_at', 'desc')->limit(10)->get(['first_name', 'last_name', 'graduation_year', 'current_occupation', 'company']);
        $donationStats = DB::table('alumni_donations')->where('school_id', $sid)->selectRaw('payment_mode, count(*) as total, sum(amount) as total_amount')->groupBy('payment_mode')->get();

        return view('alumni.reports', compact('totalAlumni', 'verifiedAlumni', 'totalDonations', 'activeJobs', 'upcomingEvents', 'yearlyBreakdown', 'recentAlumni', 'donationStats'));
    }
}
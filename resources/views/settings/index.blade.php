@extends('layouts.app')
@section('title', 'Settings')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-cog me-2"></i>School Settings</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Settings</li></ol></nav>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general">General</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#academic">Academic</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#theme">Theme</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#language">Language</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#system">System</button></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="general">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6"><x-form-input name="school_name" label="School Name" value="Springfield International School" /></div>
                                <div class="col-md-6"><x-form-input name="school_code" label="School Code" value="SIS-001" /></div>
                                <div class="col-md-6"><x-form-input name="phone" label="Phone" value="+1-555-0000" /></div>
                                <div class="col-md-6"><x-form-input name="email" label="Email" value="info@springfieldschool.edu" /></div>
                                <div class="col-md-6"><x-form-input name="address" label="Address" value="123 Education Lane, Springfield" /></div>
                                <div class="col-md-6"><x-form-input name="website" label="Website" value="www.springfieldschool.edu" /></div>
                                <div class="col-md-4"><x-form-input name="principal_name" label="Principal Name" value="Dr. Richard Williams" /></div>
                                <div class="col-md-4"><x-form-input name="vice_principal" label="Vice Principal" value="Ms. Patricia Clark" /></div>
                                <div class="col-md-4"><x-form-input name="established_year" label="Established Year" type="number" value="1995" /></div>
                                <div class="col-12"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save General Settings</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="academic">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-4"><x-form-input name="current_academic_year" label="Current Academic Year" value="2025-2026" /></div>
                                <div class="col-md-4"><x-form-input name="session_start" label="Session Start" type="date" value="2025-04-01" /></div>
                                <div class="col-md-4"><x-form-input name="session_end" label="Session End" type="date" value="2026-03-31" /></div>
                                <div class="col-md-4"><x-form-input name="exam_term" label="Current Term" value="Term 2" /></div>
                                <div class="col-md-4"><x-form-input name="grade_system" label="Grade System" value="A-F Scale" /></div>
                                <div class="col-12"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Academic Settings</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="theme">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default Theme</label>
                                    <select class="form-select">
                                        <option value="light" {{ session('theme','light')=='light'?'selected':'' }}>Light Mode</option>
                                        <option value="dark" {{ session('theme')=='dark'?'selected':'' }}>Dark Mode</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#0d6efd">
                                </div>
                                <div class="col-12"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Theme</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="language">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Default Language</label>
                                    <select class="form-select"><option>English</option><option>French</option><option>Arabic</option></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Timezone</label>
                                    <select class="form-select"><option>UTC-5 (Eastern)</option><option>UTC-6 (Central)</option><option>UTC-8 (Pacific)</option></select>
                                </div>
                                <div class="col-12"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Language Settings</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="system">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Data Retention (months)</label>
                                    <input type="number" class="form-control" value="36">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Backup Frequency</label>
                                    <select class="form-select"><option>Daily</option><option>Weekly</option><option>Monthly</option></select>
                                </div>
                                <div class="col-12"><button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save System Settings</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-circle bg-primary mx-auto mb-3" style="width:80px;height:80px;font-size:2rem"><i class="fas fa-school"></i></div>
                <h5 class="fw-bold">Springfield International</h5>
                <p class="text-muted small">Est. 1995 | School Code: SIS-001</p>
                <hr>
                <div class="text-start">
                    <div class="d-flex justify-content-between mb-2"><small>Students</small><span class="fw-bold">1,248</span></div>
                    <div class="d-flex justify-content-between mb-2"><small>Staff</small><span class="fw-bold">96</span></div>
                    <div class="d-flex justify-content-between mb-2"><small>Classes</small><span class="fw-bold">42</span></div>
                    <div class="d-flex justify-content-between"><small>Academic Year</small><span class="fw-bold">2025-2026</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
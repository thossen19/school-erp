@extends("layouts.app")

@section("title", "Create Employee")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Create Employee</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
</div>

<form action="{{ route('employees.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-input name="first_name" label="First Name" required value="{{ old('first_name') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="last_name" label="Last Name" required value="{{ old('last_name') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="employee_no" label="Employee No." required value="{{ old('employee_no') }}" placeholder="e.g. EMP-0001" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="email" label="Email" type="email" required value="{{ old('email') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="phone" label="Phone" required value="{{ old('phone') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="gender" label="Gender" required :options="['male'=>'Male','female'=>'Female','other'=>'Other']" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="date_of_birth" label="Date of Birth" type="date" required value="{{ old('date_of_birth') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="marital_status" label="Marital Status" :options="[''=>'Select','single'=>'Single','married'=>'Married','divorced'=>'Divorced','widowed'=>'Widowed']" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="blood_group" label="Blood Group" :options="[''=>'Select','A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-','O+'=>'O+','O-'=>'O-']" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="nationality" label="Nationality" value="{{ old('nationality', 'Indian') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2 text-primary"></i>Employment Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-select name="department_id" label="Department" required :options="$departments->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="designation_id" label="Designation" required :options="$designations->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="employment_type" label="Employment Type" required :options="['permanent'=>'Permanent','contract'=>'Contract','temporary'=>'Temporary','probation'=>'Probation','intern'=>'Intern']" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="date_of_joining" label="Date of Joining" type="date" required value="{{ old('date_of_joining') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="qualification" label="Qualification" value="{{ old('qualification') }}" placeholder="e.g. M.Sc." />
                </div>
                <div class="col-md-4">
                    <x-form-input name="experience_years" label="Experience (years)" type="number" value="{{ old('experience_years', 0) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="work_shift" label="Work Shift" :options="['general'=>'General','morning'=>'Morning','evening'=>'Evening','night'=>'Night']" />
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Address</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <x-form-textarea name="address" label="Address" rows="2">{{ old('address') }}</x-form-textarea>
                </div>
                <div class="col-md-4">
                    <x-form-input name="city" label="City" value="{{ old('city') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="state" label="State" value="{{ old('state') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="country" label="Country" value="{{ old('country', 'India') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="pincode" label="Pincode" value="{{ old('pincode') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Employee</button>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection

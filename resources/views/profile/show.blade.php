@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-circle me-2"></i>My Profile</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
    <div>
        <span class="badge bg-{{ $user->status ? 'success' : 'danger' }} fs-6 px-3 py-2">
            <i class="fas fa-circle me-1" style="font-size:0.6rem"></i>{{ $user->status ? 'Active' : 'Inactive' }}
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-4">
                <div class="position-relative d-inline-block mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle shadow-sm" style="width:120px;height:120px;object-fit:cover">
                    @else
                        <div class="avatar-circle bg-primary mx-auto d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width:120px;height:120px;font-size:3rem;color:#fff">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatarForm" style="display:none">
                        @csrf
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                    </form>
                    <button class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 end-0 shadow-sm" onclick="document.getElementById('avatarInput').click()" title="Change Avatar">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->profile?->full_name ?: $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                @if($user->user_type)
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ ucfirst($user->user_type) }}</span>
                @endif
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="fas fa-phone me-1"></i>Phone</span>
                        <span class="fw-medium">{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="fas fa-calendar me-1"></i>Member Since</span>
                        <span class="fw-medium">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-globe me-1"></i>Last Login</span>
                        <span class="fw-medium">{{ $user->updated_at?->diffForHumans() ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($user->profile)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-info-circle me-2"></i>Personal Info</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Gender</span>
                    <span class="fw-medium">{{ ucfirst($user->profile->gender ?? 'Not set') }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Blood Group</span>
                    <span class="fw-medium">{{ $user->profile->blood_group ?? 'Not set' }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Date of Birth</span>
                    <span class="fw-medium">{{ $user->profile->date_of_birth?->format('M d, Y') ?? 'Not set' }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Nationality</span>
                    <span class="fw-medium">{{ $user->profile->nationality ?? 'Not set' }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Religion</span>
                    <span class="fw-medium">{{ $user->profile->religion ?? 'Not set' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Marital Status</span>
                    <span class="fw-medium">{{ ucfirst($user->profile->marital_status ?? 'Not set') }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-xl-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                <i class="fas fa-user-edit me-2 text-primary fs-5"></i>
                <h6 class="fw-semibold mb-0">Edit Profile</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Username</label>
                            <input type="text" value="{{ $user->username ?? '' }}" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($user->profile)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                <i class="fas fa-address-card me-2 text-info fs-5"></i>
                <h6 class="fw-semibold mb-0">Additional Information</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->profile->first_name) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->profile->last_name) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile->date_of_birth?->format('Y-m-d')) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" {{ $user->profile->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->profile->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $user->profile->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="">Select</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ $user->profile->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Nationality</label>
                            <input type="text" name="nationality" value="{{ old('nationality', $user->profile->nationality) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Religion</label>
                            <input type="text" name="religion" value="{{ old('religion', $user->profile->religion) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Marital Status</label>
                            <select name="marital_status" class="form-select">
                                <option value="">Select</option>
                                <option value="single" {{ $user->profile->marital_status == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ $user->profile->marital_status == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ $user->profile->marital_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ $user->profile->marital_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Mother Tongue</label>
                            <input type="text" name="mother_tongue" value="{{ old('mother_tongue', $user->profile->mother_tongue) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Address</label>
                            <textarea name="address" rows="2" class="form-control">{{ old('address', $user->profile->address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->profile->city) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">State</label>
                            <input type="text" name="state" value="{{ old('state', $user->profile->state) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $user->profile->pincode) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-info px-4">
                                <i class="fas fa-save me-1"></i>Save Additional Info
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                <i class="fas fa-lock me-2 text-warning fs-5"></i>
                <h6 class="fw-semibold mb-0">Change Password</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-key me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                <i class="fas fa-shield-alt me-2 text-success fs-5"></i>
                <h6 class="fw-semibold mb-0">Active Sessions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Device</th>
                                <th>IP Address</th>
                                <th>Last Activity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fab fa-chrome me-2 text-primary"></i>Chrome on Windows
                                </td>
                                <td class="text-muted">{{ request()->ip() }}</td>
                                <td class="text-muted">Current Session</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
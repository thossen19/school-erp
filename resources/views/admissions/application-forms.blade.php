@extends('layouts.app')
@section('title', 'Application Forms')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Application Forms</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Application Forms</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" onclick="resetForm()"><i class="fas fa-plus me-1"></i>New Application</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search name / form no..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option><option value="admitted" {{ request('status')=='admitted'?'selected':'' }}>Admitted</option></select></div>
            <div class="col-md-2"><select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Form No','Applicant','Photo','Class','Phone','Status','Applied Date','Actions']">
            @forelse($applications as $a)
            <tr>
                <td>{{ $loop->iteration + ($applications->currentPage()-1)*$applications->perPage() }}</td>
                <td><small class="text-muted">{{ $a->form_number }}</small></td>
                <td class="fw-semibold">{{ $a->applicant_name }}</td>
                <td>
                    @if($a->photo)
                        <img src="{{ asset('storage/'.$a->photo) }}" alt="Photo" style="width:40px;height:40px;object-fit:cover;border-radius:50%">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $a->class_name ?? '-' }}</td>
                <td>{{ $a->phone }}</td>
                <td><span class="badge bg-{{ $a->status=='admitted'?'success':($a->status=='approved'?'info':($a->status=='rejected'?'danger':'warning')) }}">{{ ucfirst($a->status) }}</span></td>
                <td>{{ \Carbon\Carbon::parse($a->applied_date)->format('M d, Y') }}</td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('admissions.application-forms.print', $a->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Print"><i class="fas fa-print"></i></a>
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal" onclick='viewForm(@json($a))'><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#formModal" onclick='editForm(@json($a))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admissions.application-forms.destroy', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this application?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No applications found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$applications" />

{{-- Create/Edit Modal --}}
<x-modal id="formModal" title="<span id='formModalTitle'>New Application</span>">
    <form method="POST" action="{{ route('admissions.application-forms.store') }}" id="appForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="formMethodField" value="POST">
        <input type="hidden" name="id" id="formId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="applicant_name" label="Applicant Name" required /></div>
            <div class="col-md-3"><x-form-input name="date_of_birth" label="DOB" type="date" required /></div>
            <div class="col-md-3"><x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" required /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="birth_cert_no" label="Birth Certificate No." /></div>
            <div class="col-md-4"><x-form-input name="phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="email" label="Email" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-select name="class_applying_for_id" label="Applying for Class" :options="$classes->pluck('name','id')->toArray()" /></div>
        </div>
        <div class="mb-2">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewFormPhoto(event)">
            <img id="formPhotoPreview" class="mt-2 rounded" style="max-height:100px;display:none">
        </div>
        <x-form-textarea name="address" label="Address" rows="2" />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="father_name" label="Father Name" /></div>
            <div class="col-md-6"><x-form-input name="father_phone" label="Father Phone" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="mother_name" label="Mother Name" /></div>
            <div class="col-md-6"><x-form-input name="mother_phone" label="Mother Phone" /></div>
        </div>
        <x-form-input name="previous_school" label="Previous School" />
        <x-form-select name="status" label="Status" :options="['pending'=>'Pending','waiting'=>'Waiting','approved'=>'Approved','rejected'=>'Rejected','admitted'=>'Admitted']" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#appForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

{{-- View Modal --}}
<x-modal id="viewModal" title="Application Details">
    <div id="appDetails" class="small"></div>
    <x-slot:footer><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetForm() {
    $('#formModalTitle').text('New Application');
    $('#appForm').attr('action', '{{ route('admissions.application-forms.store') }}');
    $('#formMethodField').val('POST');
    $('#formId').val('');
    $('#appForm')[0].reset();
}
function editForm(a) {
    $('#formModalTitle').text('Edit Application');
    $('#appForm').attr('action', '{{ url('admissions/application-forms/update') }}/' + a.id);
    $('#formMethodField').val('PUT');
    $('#formId').val(a.id);
    $('#applicant_name').val(a.applicant_name);
    $('#date_of_birth').val(a.date_of_birth);
    $('#birth_cert_no').val(a.birth_cert_no||'');
    $('#gender').val(a.gender);
    $('#phone').val(a.phone);
    $('#email').val(a.email||'');
    $('#class_applying_for_id').val(a.class_applying_for_id||'');
    $('#address').val(a.address||'');
    $('#father_name').val(a.father_name||'');
    $('#father_phone').val(a.father_phone||'');
    $('#mother_name').val(a.mother_name||'');
    $('#mother_phone').val(a.mother_phone||'');
    $('#previous_school').val(a.previous_school||'');
    $('#status').val(a.status);
    if (a.photo) {
        var img = document.getElementById('formPhotoPreview');
        img.src = '/storage/' + a.photo;
        img.style.display = 'inline-block';
    }
}
function viewForm(a) {
    var h = '<table class="table table-sm table-bordered mb-0">';
    h += '<tr><th style="width:40%">Photo</th><td>' + (a.photo ? '<img src="/storage/' + a.photo + '" style="max-height:80px;border-radius:4px">' : '-') + '</td></tr>';
    h += '<tr><th>Form No</th><td>' + a.form_number + '</td></tr>';
    h += '<tr><th>Name</th><td>' + a.applicant_name + '</td></tr>';
    h += '<tr><th>DOB</th><td>' + a.date_of_birth + '</td></tr>';
    h += '<tr><th>Birth Cert No.</th><td>' + (a.birth_cert_no||'-') + '</td></tr>';
    h += '<tr><th>Gender</th><td>' + a.gender + '</td></tr>';
    h += '<tr><th>Phone</th><td>' + a.phone + '</td></tr>';
    h += '<tr><th>Email</th><td>' + (a.email||'-') + '</td></tr>';
    h += '<tr><th>Status</th><td>' + a.status + '</td></tr>';
    h += '<tr><th>Applied</th><td>' + a.applied_date + '</td></tr>';
    h += '</table>';
    $('#appDetails').html(h);
}

function previewFormPhoto(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            var img = document.getElementById('formPhotoPreview');
            img.src = ev.target.result;
            img.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
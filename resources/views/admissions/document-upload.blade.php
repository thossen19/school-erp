@extends('layouts.app')
@section('title', 'Document Upload')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-upload me-2"></i>Document Upload</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Documents</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#docModal"><i class="fas fa-plus me-1"></i>Upload Document</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><select name="form_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Forms</option>@foreach($forms as $f)<option value="{{ $f->id }}" {{ request('form_id')==$f->id?'selected':'' }}>{{ $f->form_number }} - {{ $f->applicant_name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="doc_type" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Types</option><option value="birth_certificate" {{ request('doc_type')=='birth_certificate'?'selected':'' }}>Birth Certificate</option><option value="photo" {{ request('doc_type')=='photo'?'selected':'' }}>Photo</option><option value="id_proof" {{ request('doc_type')=='id_proof'?'selected':'' }}>ID Proof</option><option value="address_proof" {{ request('doc_type')=='address_proof'?'selected':'' }}>Address Proof</option><option value="previous_marksheet" {{ request('doc_type')=='previous_marksheet'?'selected':'' }}>Previous Marksheet</option></select></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Form No','Applicant','Document Type','File','Uploaded','Actions']">
            @forelse($documents as $d)
            <tr>
                <td>{{ $loop->iteration + ($documents->currentPage()-1)*$documents->perPage() }}</td>
                <td><small class="text-muted">{{ $d->form_number }}</small></td>
                <td class="fw-semibold">{{ $d->applicant_name }}</td>
                <td>{{ str_replace('_',' ',ucfirst($d->document_type)) }}</td>
                <td><a href="{{ asset('storage/'.$d->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a></td>
                <td>{{ \Carbon\Carbon::parse($d->created_at)->format('M d, Y') }}</td>
                <td>
                    <form method="POST" action="{{ route('admissions.document-upload.delete', $d->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No documents uploaded</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$documents" />

<x-modal id="docModal" title="Upload Document">
    <form method="POST" action="{{ route('admissions.document-upload.store') }}" enctype="multipart/form-data">
        @csrf
        <x-form-select name="admission_form_id" label="Application" :options="$forms->pluck('applicant_name','id')->toArray()" required />
        <x-form-select name="document_type" label="Document Type" :options="['birth_certificate'=>'Birth Certificate','photo'=>'Photo','id_proof'=>'ID Proof','address_proof'=>'Address Proof','previous_marksheet'=>'Previous Marksheet']" required />
        <div class="mb-2"><label class="form-label small">File (jpeg,png,jpg,pdf,doc)</label><input type="file" name="document_file" class="form-control form-control-sm" required></div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#docModal form').submit()">Upload</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection

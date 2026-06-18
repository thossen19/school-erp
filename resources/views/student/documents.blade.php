@extends('layouts.app')
@section('title', 'Student Documents')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Student Documents</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Documents</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal"><i class="fas fa-upload me-1"></i>Upload Document</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Document Name','Student','Type','Size','Uploaded Date','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><i class="fas fa-file-pdf text-danger me-1"></i> {{ ['Birth Certificate','Report Card','Transfer Certificate','Photo ID','Medical Record','Aadhar Card','Passport','Fee Receipt'][$i-1] }}.{{ ['pdf','docx','jpg','pdf','pdf','png','pdf','pdf'][$i-1] }}</td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td>{{ ['PDF','DOCX','Image','PDF','PDF','PNG','PDF','PDF'][$i-1] }}</td>
                <td>{{ rand(100,2000) }} KB</td>
                <td>Jun {{ $i }}, 2026</td>
                <td><span class="badge bg-success">Verified</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></button>
                        <button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="uploadDocModal" title="Upload Document">
    <form>
        <x-form-select name="student" label="Select Student" :options="['1'=>'John Doe','2'=>'Jane Smith']" required />
        <x-form-input name="document_name" label="Document Name" required />
        <x-form-select name="type" label="Document Type" :options="['birth_cert'=>'Birth Certificate','report_card'=>'Report Card','transfer_cert'=>'Transfer Certificate','photo'=>'Photo','medical'=>'Medical Record','id_proof'=>'ID Proof','other'=>'Other']" />
        <div class="mb-3"><label class="form-label">File <span class="text-danger">*</span></label><input type="file" class="form-control"></div>
    </form>
    <x-slot:footer><button class="btn btn-primary">Upload</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
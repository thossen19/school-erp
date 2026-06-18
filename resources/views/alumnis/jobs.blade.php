@extends('layouts.app')
@section('title', 'Alumni Jobs')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-briefcase me-2"></i>Alumni Jobs</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item active">Jobs</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postJobModal"><i class="fas fa-plus me-1"></i>Post Job</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Job Title','Company','Location','Type','Posted By','Posted Date','Deadline','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Software Engineer','Marketing Manager','Teacher','Graphic Designer','Accountant'][$i-1] }}</td>
                <td>{{ ['Tech Corp','Business Inc.','School','Design Studio','Finance Co.'][$i-1] }}</td>
                <td>{{ ['New York, NY','Chicago, IL','Boston, MA','San Francisco, CA','Seattle, WA'][$i-1] }}</td>
                <td>{{ ['Full-time','Full-time','Contract','Part-time','Full-time'][$i-1] }}</td>
                <td>Alumni {{ $i+10 }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>Jul {{ $i }}, 2026</td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="postJobModal" title="Post Job">
    <form>
        <x-form-input name="title" label="Job Title" required />
        <x-form-input name="company" label="Company" />
        <x-form-input name="location" label="Location" />
        <x-form-select name="type" label="Type" :options="['full_time'=>'Full-time','part_time'=>'Part-time','contract'=>'Contract']" />
        <x-form-textarea name="description" label="Description" rows="4" />
        <x-form-input name="deadline" label="Application Deadline" type="date" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Post</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
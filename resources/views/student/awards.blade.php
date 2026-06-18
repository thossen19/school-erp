@extends('layouts.app')
@section('title', 'Student Awards')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Awards & Achievements</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Awards</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAwardModal"><i class="fas fa-plus me-1"></i>Add Award</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Award Title','Category','Date','Awarded By','Certificate','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">Student {{ $i }}</a></td>
                <td>{{ ['Best Student','Science Fair Winner','Math Olympiad','Sports Champion','Debate Winner','Perfect Attendance'][$i-1] }}</td>
                <td>{{ ['Academic','Science','Mathematics','Sports','Debate','Attendance'][$i-1] }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ ['Principal','Vice Principal','Head of Dept.','Coach','Club Head','Class Teacher'][$i-1] }}</td>
                <td><a href="#" class="btn btn-sm btn-outline-success"><i class="fas fa-file-pdf"></i></a></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="addAwardModal" title="Add Award">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" required />
        <x-form-input name="title" label="Award Title" required />
        <x-form-select name="category" label="Category" :options="['academic'=>'Academic','sports'=>'Sports','arts'=>'Arts','science'=>'Science','attendance'=>'Attendance','other'=>'Other']" />
        <x-form-input name="award_date" label="Award Date" type="date" />
        <x-form-input name="awarded_by" label="Awarded By" />
        <x-form-textarea name="description" label="Description" rows="2" />
        <div class="mb-3"><label class="form-label">Certificate (optional)</label><input type="file" class="form-control"></div>
    </form>
    <x-slot:footer><button class="btn btn-primary">Save Award</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
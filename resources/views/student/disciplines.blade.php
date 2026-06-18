@extends('layouts.app')
@section('title', 'Student Disciplines')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-gavel me-2"></i>Discipline Records</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Disciplines</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDisciplineModal"><i class="fas fa-plus me-1"></i>Add Record</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Incident Type','Description','Date','Action Taken','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td><span class="badge bg-{{ ['warning','danger','info','secondary'][$i%4] }}">{{ ['Misconduct','Absenteeism','Uniform Violation','Late Coming','Bullying'][$i-1]??'Other' }}</span></td>
                <td>{{ ['Disruptive behavior in class','Unauthorized absence','Wrong uniform','Late 3 times this week','Verbal misconduct'][$i-1]??'-' }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ ['Warning','Parent Meeting','Detention','Suspension','Counseling'][$i-1]??'Verbal Warning' }}</td>
                <td><span class="badge bg-{{ ['warning','success','danger','info'][$i%4] }}">{{ ['Pending','Resolved','Escalated','Under Review'][$i%4] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="addDisciplineModal" title="Add Discipline Record">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" required />
        <x-form-select name="type" label="Incident Type" :options="['misconduct'=>'Misconduct','absenteeism'=>'Absenteeism','uniform'=>'Uniform Violation','late'=>'Late Coming','bullying'=>'Bullying','other'=>'Other']" />
        <x-form-textarea name="description" label="Description" rows="3" required />
        <x-form-input name="incident_date" label="Incident Date" type="date" />
        <x-form-select name="action" label="Action Taken" :options="['warning'=>'Verbal Warning','written'=>'Written Warning','detention'=>'Detention','suspension'=>'Suspension','meeting'=>'Parent Meeting','counseling'=>'Counseling']" />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save Record</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
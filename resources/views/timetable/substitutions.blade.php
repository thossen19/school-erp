@extends('layouts.app')
@section('title', 'Substitutions')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Substitutions</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('timetable.index') }}">Timetable</a></li><li class="breadcrumb-item active">Substitutions</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubModal"><i class="fas fa-plus me-1"></i>New Substitution</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Date','Period','Class','Subject','Regular Teacher','Substitute Teacher','Reason','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td>Jun {{ $i+10 }}, 2026</td>
                <td>{{ rand(1,8) }}</td>
                <td>Grade {{ rand(1,12) }}{{ ['A','B','C'][$i%3] }}</td>
                <td>{{ ['Math','English','Science','History'][$i%4] }}</td>
                <td>{{ ['Mr. Johnson','Ms. Davis','Mrs. Smith'][$i%3] }}</td>
                <td><span class="fw-semibold">{{ ['Mr. Brown','Ms. Lee','Dr. Clark','Mrs. Taylor','Mr. Wilson'][$i%5] }}</span></td>
                <td>{{ ['Sick Leave','Training','Personal','Emergency','Meeting','Conference'][$i-1] }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Approved','Pending','Completed'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addSubModal" title="New Substitution">
    <form>
        <x-form-input name="date" label="Date" type="date" />
        <x-form-select name="class" label="Class" :options="['10A'=>'Grade 10A']" />
        <x-form-select name="period" label="Period" :options="['1'=>'Period 1','2'=>'Period 2','3'=>'Period 3']" />
        <x-form-select name="regular_teacher" label="Regular Teacher" :options="['1'=>'Mr. Johnson']" />
        <x-form-select name="substitute" label="Substitute Teacher" :options="['2'=>'Ms. Lee','3'=>'Mr. Brown']" required />
        <x-form-textarea name="reason" label="Reason" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
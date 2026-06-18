@extends('layouts.app')
@section('title', 'Room Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-door-open me-2"></i>Room Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('timetable.index') }}">Timetable</a></li><li class="breadcrumb-item active">Room Allocation</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#allocRoomModal"><i class="fas fa-plus me-1"></i>Allocate Room</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Room No.','Capacity','Floor','Building','Current Allocation','Status','Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><span class="fw-semibold">Room {{ 200+$i }}</span></td>
                <td>{{ rand(30,50) }}</td>
                <td>Floor {{ rand(1,3) }}</td>
                <td>{{ ['Main Building','Science Block','Arts Wing'][$i%3] }}</td>
                <td>{{ ['Grade 10A','Grade 9B','-','Grade 8A','-','Grade 11C','-','Grade 7B','-','Grade 12A'][$i-1] }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Allocated','Available','Partially'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="allocRoomModal" title="Allocate Room">
    <form>
        <x-form-select name="room" label="Room" :options="['201'=>'Room 201','202'=>'Room 202']" />
        <x-form-select name="class" label="Assign To Class" :options="['10A'=>'Grade 10A']" />
        <x-form-select name="period" label="Period(s)" :options="['all'=>'All Periods','morning'=>'Morning Only','afternoon'=>'Afternoon Only']" />
        <x-form-textarea name="notes" label="Notes" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Allocate</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
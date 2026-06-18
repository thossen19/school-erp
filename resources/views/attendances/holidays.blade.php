@extends('layouts.app')
@section('title', 'Holidays')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-day me-2"></i>Holidays</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Holidays</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal"><i class="fas fa-plus me-1"></i>Add Holiday</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Holiday Name','Date','Day','Type','Description','Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><span class="fw-semibold">{{ ['New Year','Republic Day','Good Friday','Easter Monday','Labor Day','Independence Day','Summer Break Start','Summer Break End','Thanksgiving','Christmas'][$i-1] }}</span></td>
                <td>2026-{{ sprintf('%02d',rand(1,12)) }}-{{ sprintf('%02d',rand(1,28)) }}</td>
                <td>{{ ['Monday','Tuesday','Wednesday','Thursday','Friday'][array_rand(['Monday','Tuesday','Wednesday','Thursday','Friday'])] }}</td>
                <td><span class="badge bg-{{ ['danger','info','success','warning','primary'][$i%5] }}">{{ ['National Holiday','Religious','School Event','Summer Break','National'][$i%5] }}</span></td>
                <td><small class="text-muted">Annual holiday</small></td>
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

<x-modal id="addHolidayModal" title="Add Holiday">
    <form>
        <x-form-input name="name" label="Holiday Name" required />
        <x-form-input name="date" label="Date" type="date" required />
        <x-form-select name="type" label="Type" :options="['national'=>'National Holiday','religious'=>'Religious','school'=>'School Event','summer'=>'Summer Break','other'=>'Other']" />
        <x-form-textarea name="description" label="Description" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Add Holiday</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
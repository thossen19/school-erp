@extends('layouts.app')
@section('title', 'Student Houses')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-home me-2"></i>Student Houses</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Houses</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHouseModal"><i class="fas fa-plus me-1"></i>Add House</button>
</div>

<div class="row g-3">
    @foreach(['Red','Blue','Green','Yellow'] as $house)
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 border-top border-{{ $house=='Red'?'danger':($house=='Blue'?'primary':($house=='Green'?'success':'warning')) }} border-3">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-2 bg-{{ $house=='Red'?'danger':($house=='Blue'?'primary':($house=='Green'?'success':'warning')) }} text-white" style="width:56px;height:56px;font-size:1.5rem"><i class="fas fa-shield-alt"></i></div>
                <h5 class="fw-bold">{{ $house }} House</h5>
                <p class="text-muted small">Captain: {{ ['John S.','Emma W.','Michael B.','Sophia L.'][$loop->index] }}</p>
                <hr>
                <div class="d-flex justify-content-around">
                    <div><small class="text-muted d-block">Students</small><span class="fw-bold fs-5">{{ rand(200,350) }}</span></div>
                    <div><small class="text-muted d-block">Points</small><span class="fw-bold fs-5">{{ rand(500,1500) }}</span></div>
                </div>
                <hr>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-users"></i> View Members</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>House Members</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No.','House','Class','Points']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">Student {{ $i }}</a></td>
                <td>ADM-{{ sprintf('%04d',$i) }}</td>
                <td><span class="badge bg-{{ ['danger','primary','success','warning'][$i%4] }}">{{ ['Red','Blue','Green','Yellow'][$i%4] }} House</span></td>
                <td>Grade {{ rand(1,12) }}</td>
                <td>{{ rand(50,500) }}</td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="addHouseModal" title="Add New House">
    <form>
        <x-form-input name="name" label="House Name" required placeholder="e.g. Purple House" />
        <x-form-input name="color" label="Color" type="color" value="#6f42c1" />
        <x-form-input name="captain" label="House Captain" placeholder="Student name" />
        <x-form-textarea name="description" label="Description" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save House</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
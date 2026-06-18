@extends('layouts.app')
@section('title', 'Student Promotions')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-arrow-up me-2"></i>Student Promotions</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Promotions</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#promoteModal"><i class="fas fa-arrow-up me-1"></i>Promote Students</button>
        <button class="btn btn-outline-info"><i class="fas fa-history me-1"></i>Promotion History</button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold small">From Class</label><select class="form-select form-select-sm"><option>Grade 10</option><option>Grade 9</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">To Class</label><select class="form-select form-select-sm"><option>Grade 11</option><option>Grade 10</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Academic Year</label><select class="form-select form-select-sm"><option>2025-2026</option></select></div>
            <div class="col-md-2"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i> Load Students</button></div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No.','From Class','To Class','Status','Promotion Date','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td>ADM-{{ sprintf('%04d',$i) }}</td>
                <td>Grade {{ rand(1,11) }}</td>
                <td>Grade {{ rand(2,12) }}</td>
                <td><span class="badge bg-{{ ['success','warning'][$i%2] }}">{{ ['Promoted','Pending'][$i%2] }}</span></td>
                <td>Jun {{ $i }}, 2026</td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="promoteModal" title="Promote Students">
    <form>
        <x-form-select name="from_class" label="From Class" :options="['10'=>'Grade 10','9'=>'Grade 9']" />
        <x-form-select name="to_class" label="To Class" :options="['11'=>'Grade 11','10'=>'Grade 10']" />
        <x-form-select name="academic_year" label="Academic Year" :options="['2026'=>'2025-2026']" />
        <x-form-select name="promotion_type" label="Promotion Type" :options="['auto'=>'Automatic','exam'=>'Based on Exam','manual'=>'Manual']" />
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="sendNotification"><label class="form-check-label" for="sendNotification">Send notification to parents</label></div>
    </form>
    <x-slot:footer><button class="btn btn-primary">Process Promotion</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
@extends('layouts.app')
@section('title', 'Merit List Generation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Merit List Generation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Merit List</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal"><i class="fas fa-cog me-1"></i>Generate New</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Exam Results Ranking</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['Rank','Applicant','Form No','Exam','Marks','Percentage']">
            @forelse($examResults as $i=>$r)
            <tr>
                <td><span class="badge bg-{{ $i<3?'warning':'secondary' }}">#{{ $i+1 }}</span></td>
                <td class="fw-semibold">{{ $r->applicant_name }}</td>
                <td><small class="text-muted">{{ $r->form_number }}</small></td>
                <td>{{ $r->exam_title }}</td>
                <td>{{ $r->marks_obtained }}/{{ $r->total_marks }}</td>
                <td><span class="badge bg-{{ $r->percentage>=80?'success':($r->percentage>=50?'warning':'danger') }}">{{ round($r->percentage,1) }}%</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No exam results found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Generated Merit Lists</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Class','Entries','Generated','Status']">
            @forelse($meritLists as $ml)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $ml->title }}</td>
                <td>{{ $ml->class_name ?? 'All' }}</td>
                <td>{{ count(json_decode($ml->ranks??'[]')) }}</td>
                <td>{{ \Carbon\Carbon::parse($ml->generated_at)->format('M d, Y H:i') }}</td>
                <td><span class="badge bg-{{ $ml->status=='published'?'success':'secondary' }}">{{ ucfirst($ml->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No merit lists generated</td></tr>
            @endforelse
        </x-table>
    </div>
</div>

<x-modal id="generateModal" title="Generate Merit List">
    <form method="POST" action="{{ route('admissions.merit-list-generation.generate') }}">
        @csrf
        <x-form-input name="title" label="List Title" placeholder="e.g. 2026-27 Merit List" required />
        <x-form-select name="class_id" label="Class (optional)" :options="$examResults->pluck('class_applying_for_id')->unique()->mapWithKeys(function($v){ return [$v => 'Class '.$v]; })->toArray()" placeholder="All Classes" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#generateModal form').submit()">Generate</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection

@extends('layouts.app')
@section('title', 'Student Groups')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-layer-group me-2"></i>Student Groups</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Groups</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal"><i class="fas fa-plus me-1"></i>Create Group</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Group Name','Description','Members','Created By','Created Date','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><span class="fw-semibold">{{ ['Science Club','Math Olympiad','Debate Team','Art Club','Music Band','Sports Team'][$i-1] }}</span></td>
                <td>{{ ['STEM-focused group','Advanced math practice','Public speaking','Creative arts','Instrumental music','Athletics'][$i-1] }}</td>
                <td><span class="badge bg-info">{{ rand(10,50) }}</span></td>
                <td>Teacher {{ $i }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="addGroupModal" title="Create Student Group">
    <form>
        <x-form-input name="name" label="Group Name" required />
        <x-form-textarea name="description" label="Description" rows="2" />
        <x-form-select name="members" label="Select Members" :options="['all'=>'All Students','class'=>'By Class','manual'=>'Manual Select']" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Create Group</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
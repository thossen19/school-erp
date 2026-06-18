@extends('layouts.app')
@section('title', 'Book Issues')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-open me-2"></i>Book Issues</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item active">Issues</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issueModal"><i class="fas fa-plus me-1"></i>Issue Book</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Book','Member','Issue Date','Due Date','Return Date','Status','Fine','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">Book {{ $i }}</a></td>
                <td>Member {{ $i }}</td>
                <td>May {{ $i }}, 2026</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ $i%3==0?'Jun '.($i+5).', 2026':'-' }}</td>
                <td><span class="badge bg-{{ ['warning','danger','success'][$i%3] }}">{{ ['Issued','Overdue','Returned'][$i%3] }}</span></td>
                <td>{{ $i%3==1?'$'.rand(1,10):'-' }}</td>
                <td><button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Return</button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="issueModal" title="Issue Book">
    <form>
        <x-form-select name="book" label="Book" :options="['1'=>'To Kill a Mockingbird']" />
        <x-form-select name="member" label="Member" :options="['1'=>'John Doe (Student)','2'=>'Jane Smith (Teacher)']" />
        <x-form-input name="issue_date" label="Issue Date" type="date" value="{{ date('Y-m-d') }}" />
        <x-form-input name="due_date" label="Due Date" type="date" value="{{ date('Y-m-d', strtotime('+14 days')) }}" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Issue Book</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
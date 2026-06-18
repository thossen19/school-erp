@extends('layouts.app')
@section('title', 'Library Fines')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-coins me-2"></i>Library Fines</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item active">Fines</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Member','Book','Days Overdue','Fine Amount','Paid Date','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td>Member {{ $i }}</td>
                <td>Book {{ $i }}</td>
                <td>{{ rand(5,30) }} days</td>
                <td class="fw-bold text-danger">${{ rand(5,50) }}</td>
                <td>{{ $i%2==0?'Jun '.$i.', 2026':'-' }}</td>
                <td><span class="badge bg-{{ $i%2==0?'success':'warning' }}">{{ $i%2==0?'Paid':'Unpaid' }}</span></td>
                <td><button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Mark Paid</button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
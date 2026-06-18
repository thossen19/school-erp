@extends('layouts.app')
@section('title', 'Fee Due Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Fee Due Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Due Tracking</li></ol></nav>
    </div>
    <button class="btn btn-outline-success"><i class="fas fa-bell me-1"></i>Send Reminders</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Class','Fee Head','Amount','Due Date','Days Overdue','Status','Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">Student {{ $i }}</a></td>
                <td>Grade {{ rand(1,12) }}</td>
                <td>{{ ['Tuition','Transport','Lab','Sports'][$i%4] }}</td>
                <td class="fw-bold">${{ number_format(rand(100,2500),2) }}</td>
                <td>May {{ $i }}, 2026</td>
                <td><span class="badge bg-{{ $i<=4?'warning':($i<=7?'danger':'dark') }}">{{ rand(5,30) }} days</span></td>
                <td><span class="badge bg-{{ $i<=3?'warning':'danger' }}">{{ $i<=3?'Pending':'Overdue' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Pay</button>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
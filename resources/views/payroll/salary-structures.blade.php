@extends('layouts.app')
@section('title', 'Salary Structures')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-coins me-2"></i>Salary Structures</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Payroll</a></li><li class="breadcrumb-item active">Salary Structures</li></ol></nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm">+ Add Structure</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Basic Salary','Net Salary','Total Amount','Status']">
            @forelse($structures as $s)
            <tr>
                <td class="fw-semibold">{{ $s->name }}</td>
                <td>{{ number_format($s->basic_salary, 2) }}</td>
                <td>{{ number_format($s->net_salary, 2) }}</td>
                <td>{{ number_format($s->total_amount, 2) }}</td>
                <td><span class="badge bg-{{ $s->is_active ? 'success' : 'danger' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No salary structures found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$structures" />
</div>
@endsection

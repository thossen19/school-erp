@extends('layouts.app')
@section('title', 'Stock Audit')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-list me-2"></i>Stock Audit</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item active">Stock Audit</li></ol></nav>
    </div>
    <button class="btn btn-primary"><i class="fas fa-plus me-1"></i>New Audit</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Item','System Qty','Physical Qty','Variance','Audited By','Date','Status','Actions']">
            @foreach(range(1,8) as $i)
            @php $sys = rand(10,100); $phy = $sys + rand(-5,5); @endphp
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Notebooks','Pens','Desks','Chairs','Markers','Whiteboards','Computers','Printers'][$i-1] }}</td>
                <td>{{ $sys }}</td>
                <td>{{ max(0,$phy) }}</td>
                <td class="fw-bold text-{{ $sys==$phy?'success':($sys>$phy?'danger':'warning') }}">{{ $sys==$phy?'0':($sys-$phy) }}</td>
                <td>Auditor {{ $i }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td><span class="badge bg-{{ $sys==$phy?'success':'warning' }}">{{ $sys==$phy?'Matched':'Variance' }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
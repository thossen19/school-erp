@extends('layouts.app')
@section('title', 'Alumni Donations')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-donate me-2"></i>Alumni Donations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item active">Donations</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Donor','Batch','Amount','Purpose','Date','Payment Method','Receipt']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Alice Johnson','Bob Smith','Carol Davis','David Lee','Emma Wilson','Frank Brown'][$i-1] }}</td>
                <td>20{{ sprintf('%02d',rand(15,22)) }}</td>
                <td class="fw-bold text-success">${{ number_format(rand(100,5000),2) }}</td>
                <td>{{ ['Scholarship Fund','Building Fund','Library','Sports Fund','General','Lab Equipment'][$i-1] }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ ['Credit Card','Bank Transfer','Cash','Cheque','Credit Card','Bank Transfer'][$i-1] }}</td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
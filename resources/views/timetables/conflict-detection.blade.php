@extends('layouts.app')
@section('title', 'Conflict Detection')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Conflict Detection</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Conflict Detection</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="day" class="form-select form-select-sm">
                    <option value="">All Days</option>
                    @foreach($days as $d)
                    <option value="{{ $d }}" {{ $checkedDay==$d?'selected':'' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Check</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Detected Conflicts</h6>
        <span class="badge bg-{{ $conflicts->count()>0?'danger':'success' }}">{{ $conflicts->count() }} conflict{{ $conflicts->count()!=1?'s':'' }}</span>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Type','Day','Details']">
            @foreach($conflicts as $i=>$c)
            <tr>
                <td>{{ $i+1 }}</td>
                <td><span class="badge bg-{{ $c->type=='Teacher'?'warning text-dark':'danger' }}">{{ $c->type }}</span></td>
                <td>{{ $c->day }}</td>
                <td><small>{{ $c->detail }}</small></td>
            </tr>
            @endforeach
            @if($conflicts->isEmpty())<tr><td colspan="4" class="text-center text-success py-3">No conflicts detected</td></tr>@endif
        </x-table>
    </div>
</div>
@endsection

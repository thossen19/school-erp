@extends('layouts.app')
@section('title', 'Admission Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Admission Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalEnquiries }}</h5><small class="text-muted">Total Enquiries</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $totalForms }}</h5><small class="text-muted">Total Applications</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $approvedForms }}</h5><small class="text-muted">Approved</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $admittedForms }}</h5><small class="text-muted">Admitted</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-danger">{{ $rejectedForms }}</h5><small class="text-muted">Rejected</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-secondary">{{ $pendingForms }}</h5><small class="text-muted">Pending</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">${{ number_format($admissionFees,2) }}</h5><small class="text-muted">Admission Fees Collected</small></div></div>
</div>

<div class="row g-2">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-circle me-2"></i>Status Distribution</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @foreach($statusStats as $s)
                    <tr><td><span class="badge bg-{{ $s->status=='admitted'?'success':($s->status=='approved'?'info':($s->status=='rejected'?'danger':'warning')) }}">{{ ucfirst($s->status) }}</span></td><td>{{ $s->total }}</td></tr>
                    @endforeach
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-graduation-cap me-2"></i>Applications by Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Count']">
                    @foreach($classStats as $cs)
                    <tr><td>{{ $cs->name ?? 'N/A' }}</td><td>{{ $cs->total }}</td></tr>
                    @endforeach
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar me-2"></i>Monthly Applications ({{ now()->year }})</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-end gap-2" style="min-height:150px">
                    @php $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; $maxVal = max($monthlyForms->max('total'),1); @endphp
                    @for($m=1;$m<=12;$m++)
                        @php $found = $monthlyForms->firstWhere('month',$m); $cnt = $found?$found->total:0; $pct = $cnt/$maxVal*100; @endphp
                        <div class="flex-fill text-center">
                            <div class="bg-primary rounded-top" style="height:{{ max($pct,2) }}%;min-height:20px;line-height:20px;font-size:11px;color:white">{{ $cnt }}</div>
                            <small class="text-muted">{{ $months[$m-1] }}</small>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

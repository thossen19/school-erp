@extends('layouts.app')
@section('title', 'Health Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Health Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Health Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <x-stats-card icon="fas fa-heartbeat" title="Health Records" :value="$totalRecords" color="primary" />
    </div>
    <div class="col-md-3">
        <x-stats-card icon="fas fa-syringe" title="Vaccinations" :value="$totalVaccinations" color="success" />
    </div>
    <div class="col-md-3">
        <x-stats-card icon="fas fa-notes-medical" title="Medical Profiles" :value="$totalMedicalRecords" color="info" />
    </div>
    <div class="col-md-3">
        <x-stats-card icon="fas fa-pills" title="Medicines" :value="$totalMedicines" color="warning" />
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-syringe me-2 text-success"></i>Upcoming Vaccinations</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Student','Vaccine','Due Date']">
                    @forelse($upcomingVaccinations as $v)
                        <tr>
                            <td class="fw-semibold">{{ $v->first_name }} {{ $v->last_name }}</td>
                            <td>{{ $v->vaccine_name }} (Dose {{ $v->dose_number }})</td>
                            <td><span class="badge bg-warning text-dark">{{ \Carbon\Carbon::parse($v->next_due_date)->format('d-m-Y') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No upcoming vaccinations.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-stethoscope me-2 text-primary"></i>Recent Checkups</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Student','Date','Height','Weight','BP']">
                    @forelse($recentCheckups as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->first_name }} {{ $c->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($c->checkup_date)->format('d-m-Y') }}</td>
                            <td>{{ $c->height ? $c->height.' cm' : '-' }}</td>
                            <td>{{ $c->weight ? $c->weight.' kg' : '-' }}</td>
                            <td>{{ $c->blood_pressure ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No checkups recorded.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-pills me-2 text-warning"></i>Active Medications</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Student','Medicine','Dosage','Frequency']">
                    @forelse($activeMedications as $m)
                        <tr>
                            <td class="fw-semibold">{{ $m->first_name }} {{ $m->last_name }}</td>
                            <td>{{ $m->medicine_name }}</td>
                            <td>{{ $m->dosage ?? '-' }}</td>
                            <td>{{ $m->frequency ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No active medications.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-tint me-2 text-danger"></i>Blood Group Distribution</h6></div>
            <div class="card-body">
                @if($bloodGroupStats->isNotEmpty())
                    @foreach($bloodGroupStats as $bg)
                        @php
                            $pct = $totalMedicalRecords > 0 ? round(($bg->total / $totalMedicalRecords) * 100) : 0;
                            $colors = ['A+' => 'danger', 'A-' => 'secondary', 'B+' => 'warning', 'B-' => 'secondary', 'AB+' => 'info', 'AB-' => 'secondary', 'O+' => 'success', 'O-' => 'primary'];
                            $color = $colors[$bg->blood_group] ?? 'secondary';
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-{{ $color }}">{{ $bg->blood_group }}</span>
                                <small class="text-muted">{{ $bg->total }} students ({{ $pct }}%)</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">No blood group data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

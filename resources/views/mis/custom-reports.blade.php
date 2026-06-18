@extends('layouts.app')
@section('title', 'Custom Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Custom Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Custom Reports</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal"><i class="fas fa-plus me-1"></i>New Template</button>
    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="fas fa-clock me-1"></i>Schedule Report</button>
</div>
<div class="row g-3">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-file me-2 text-primary"></i>Report Templates</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Name','Type','Actions']">
                    @forelse($templates as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td class="fw-semibold">{{ $t->name }}</td>
                        <td><span class="badge bg-info">{{ $t->type }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('mis.custom-reports.delete', $t->id) }}" class="d-inline" onsubmit="return confirm('Delete this template?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No templates created yet.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
        <x-pagination :paginator="$templates" />
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-clock me-2 text-success"></i>Scheduled Reports</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Template','Frequency','Next Run','Status']">
                    @forelse($schedules as $sc)
                    <tr>
                        <td class="fw-semibold">{{ $sc->report_template_id }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($sc->frequency) }}</span></td>
                        <td><small>{{ $sc->next_run ? \Carbon\Carbon::parse($sc->next_run)->format('d-m-Y H:i') : '-' }}</small></td>
                        <td>@if($sc->status === 'active')<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No schedules configured.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('mis.custom-reports.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>New Report Template</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Template Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Type</label><select name="type" class="form-select" required><option value="student">Student</option><option value="fee">Fee</option><option value="attendance">Attendance</option><option value="exam">Exam</option><option value="employee">Employee</option><option value="custom">Custom</option></select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Config (JSON)</label><textarea name="config" class="form-control" rows="4" placeholder='{"fields":["name","class"],"filters":{}}'></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create</button>
            </div>
        </form>
    </div></div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('mis.custom-reports.schedule') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-clock me-1"></i>Schedule Report</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Report Template</label><select name="report_template_id" class="form-select" required><option value="">Select</option>@foreach($templates as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Frequency</label><select name="frequency" class="form-select" required><option value="daily">Daily</option><option value="weekly">Weekly</option><option value="monthly">Monthly</option><option value="quarterly">Quarterly</option><option value="yearly">Yearly</option></select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Recipients (comma-separated emails)</label><textarea name="recipients" class="form-control" rows="2" required></textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Format</label><select name="format" class="form-select"><option value="pdf">PDF</option><option value="excel">Excel</option><option value="csv">CSV</option></select></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Schedule</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
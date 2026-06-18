@extends('layouts.app')
@section('title', 'Entrance Exams')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-pencil-alt me-2"></i>Entrance Exams</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Entrance Exams</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#examModal" onclick="resetExamForm()"><i class="fas fa-plus me-1"></i>Add Exam</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Class','Exam Date','Duration (min)','Total Marks','Passing Marks','Status','Actions']">
            @forelse($exams as $exam)
            <tr>
                <td>{{ $loop->iteration + ($exams->currentPage()-1)*$exams->perPage() }}</td>
                <td class="fw-semibold">{{ $exam->title }}</td>
                <td>{{ $exam->class_name ?? 'All' }}</td>
                <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') }}</td>
                <td>{{ $exam->duration }}</td>
                <td>{{ $exam->total_marks }}</td>
                <td>{{ $exam->passing_marks }}</td>
                <td><span class="badge bg-{{ $exam->status ? 'success' : 'secondary' }}">{{ $exam->status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#examModal" onclick='editExam(@json($exam))'><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#resultModal" data-exam-id="{{ $exam->id }}" data-exam-title="{{ $exam->title }}"><i class="fas fa-poll"></i></button>
                        <form method="POST" action="{{ route('admissions.entrance-exam.destroy', $exam->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No entrance exams found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$exams" />

<x-modal id="examModal" title="<span id='examModalTitle'>Add Entrance Exam</span>">
    <form method="POST" action="{{ route('admissions.entrance-exam.store') }}" id="examForm">
        @csrf
        <input type="hidden" name="_method" id="examMethodField" value="POST">
        <input type="hidden" name="exam_id" id="examId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="title" label="Exam Title" required /></div>
            <div class="col-md-3"><x-form-input name="exam_date" label="Exam Date" type="date" required /></div>
            <div class="col-md-3"><x-form-input name="duration" label="Duration (min)" type="number" required /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="total_marks" label="Total Marks" type="number" step="0.01" required /></div>
            <div class="col-md-4"><x-form-input name="passing_marks" label="Passing Marks" type="number" step="0.01" required /></div>
            <div class="col-md-4"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" placeholder="All Classes" /></div>
        </div>
        <x-form-textarea name="description" label="Description" rows="2" />
        <div class="form-check"><input type="checkbox" class="form-check-input" name="status" id="examStatus" value="1" checked><label class="form-check-label small" for="examStatus">Active</label></div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#examForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

<x-modal id="resultModal" title="<span id='resultModalTitle'>Add Result</span>">
    <form method="POST" action="{{ route('admissions.exam-results.store') }}">
        @csrf
        <input type="hidden" name="entrance_exam_id" id="resultExamId">
        <x-form-input name="admission_form_id" label="Admission Form ID" type="number" required />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="marks_obtained" label="Marks Obtained" type="number" step="0.01" required /></div>
            <div class="col-md-6"><x-form-input name="total_marks" label="Total Marks" type="number" required /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary">Save Result</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetExamForm() {
    $('#examModalTitle').text('Add Entrance Exam'); $('#examForm').attr('action', '{{ route('admissions.entrance-exam.store') }}');
    $('#examMethodField').val('POST'); $('#examForm')[0].reset(); $('#examId').val(''); $('#examStatus').prop('checked', true);
}
function editExam(exam) {
    $('#examModalTitle').text('Edit Entrance Exam'); $('#examForm').attr('action', '{{ url('admissions/entrance-exam/update') }}/' + exam.id);
    $('#examMethodField').val('PUT'); $('#examId').val(exam.id);
    $('#title').val(exam.title); $('#exam_date').val(exam.exam_date); $('#duration').val(exam.duration);
    $('#total_marks').val(exam.total_marks); $('#passing_marks').val(exam.passing_marks);
    $('#class_id').val(exam.class_id||''); $('#description').val(exam.description||'');
    $('#examStatus').prop('checked', exam.status==1);
}
$('#resultModal').on('show.bs.modal', function (e) {
    var btn = $(e.relatedTarget);
    $('#resultExamId').val(btn.data('exam-id'));
    $('#resultModalTitle').text('Add Result - ' + btn.data('exam-title'));
});
</script>
@endpush
@endsection
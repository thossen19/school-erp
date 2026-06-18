@extends('layouts.app')
@section('title', 'Clubs')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-users me-2"></i>Clubs</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Clubs</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clubModal" onclick="resetClubForm()"><i class="fas fa-plus me-1"></i>Create Club</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Club Name','Type','Code','Max Members','Active Members','Status','Actions']">
            @forelse($clubs as $club)
            <tr>
                <td>{{ $loop->iteration + ($clubs->currentPage()-1)*$clubs->perPage() }}</td>
                <td class="fw-semibold">{{ $club->name }}</td>
                <td>{{ $club->club_type ?? '-' }}</td>
                <td>{{ $club->code ?? '-' }}</td>
                <td>{{ $club->max_members ?? '-' }}</td>
                <td>{{ $memberCounts[$club->id] ?? 0 }}</td>
                <td><span class="badge bg-{{ $club->status ? 'success' : 'secondary' }}">{{ $club->status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#clubModal" onclick='editClub(@json($club))'><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#memberModal" data-club-id="{{ $club->id }}" data-club-name="{{ $club->name }}"><i class="fas fa-user-plus"></i></button>
                        <form method="POST" action="{{ route('events.clubs.destroy', $club->id) }}" class="d-inline" onsubmit="return confirm('Delete this club?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No clubs found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$clubs" />

<x-modal id="clubModal" title="<span id='clubModalTitle'>Create Club</span>">
    <form method="POST" action="{{ route('events.clubs.store') }}" id="clubForm">
        @csrf
        <input type="hidden" name="_method" id="clubMethodField" value="POST">
        <input type="hidden" name="club_id" id="clubId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Club Name" required /></div>
            <div class="col-md-3"><x-form-input name="code" label="Code" /></div>
            <div class="col-md-3"><x-form-select name="club_type" label="Type" :options="['academic'=>'Academic','cultural'=>'Cultural','sports'=>'Sports','technology'=>'Technology','arts'=>'Arts','service'=>'Service','other'=>'Other']" /></div>
        </div>
        <x-form-textarea name="description" label="Description" rows="2" />
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="max_members" label="Max Members" type="number" /></div>
            <div class="col-md-8"><x-form-input name="meeting_schedule" label="Meeting Schedule" placeholder="e.g. Every Friday 3-4 PM" /></div>
        </div>
        <div class="form-check mb-2"><input type="checkbox" class="form-check-input" name="status" id="clubStatus" value="1" checked><label class="form-check-label small" for="clubStatus">Active</label></div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#clubForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

<x-modal id="memberModal" title="<span id='memberModalTitle'>Add Member</span>">
    <form method="POST" action="{{ route('events.clubs.members.store') }}" id="memberForm">
        @csrf
        <input type="hidden" name="club_id" id="memberClubId">
        <x-form-input name="student_id" label="Student ID" type="number" required />
        <x-form-select name="role" label="Role" :options="['member'=>'Member','vice_president'=>'Vice President','president'=>'President','secretary'=>'Secretary','treasurer'=>'Treasurer']" />
        <x-form-input name="joined_date" label="Joined Date" type="date" value="{{ date('Y-m-d') }}" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#memberForm').submit()">Add Member</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetClubForm() {
    $('#clubModalTitle').text('Create Club');
    $('#clubForm').attr('action', '{{ route('events.clubs.store') }}');
    $('#clubMethodField').val('POST');
    $('#clubForm')[0].reset();
    $('#clubId').val('');
    $('#clubStatus').prop('checked', true);
}
function editClub(club) {
    $('#clubModalTitle').text('Edit Club');
    $('#clubForm').attr('action', '{{ url('events/clubs/update') }}/' + club.id);
    $('#clubMethodField').val('PUT');
    $('#clubId').val(club.id);
    $('#name').val(club.name);
    $('#code').val(club.code||'');
    $('#club_type').val(club.club_type||'');
    $('#description').val(club.description||'');
    $('#max_members').val(club.max_members||'');
    $('#meeting_schedule').val(club.meeting_schedule||'');
    $('#clubStatus').prop('checked', club.status==1);
}
$('#memberModal').on('show.bs.modal', function (e) {
    var btn = $(e.relatedTarget);
    $('#memberClubId').val(btn.data('club-id'));
    $('#memberModalTitle').text('Add Member - ' + btn.data('club-name'));
});
</script>
@endpush
@endsection
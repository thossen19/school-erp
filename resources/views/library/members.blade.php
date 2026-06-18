@extends("layouts.app")

@section("title", "Library Members")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-id-card me-2"></i>Library Members</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">Members</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Membership No','Name','Admission No','Member Type','Joining Date','Valid Until','Status']">
            @forelse($members as $member)
                <tr>
                    <td>{{ $member->membership_no }}</td>
                    <td>{{ $member->first_name ?? '' }} {{ $member->last_name ?? '' }}</td>
                    <td>{{ $member->admission_no ?? '-' }}</td>
                    <td>{{ ucfirst($member->member_type ?? $member->membership_type) }}</td>
                    <td>{{ $member->joining_date ? date('d M Y', strtotime($member->joining_date)) : '-' }}</td>
                    <td>{{ $member->valid_until ? date('d M Y', strtotime($member->valid_until)) : '-' }}</td>
                    <td><span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No members found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$members" />
</div>
@endsection

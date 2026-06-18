@extends('layouts.app')

@section('title', 'Custom Pages')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-0">Custom Pages</h3>
        <a href="{{ route('custom-pages.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>New Page</a>
    </div>
    <p class="text-muted">Create and design custom homepage pages with drag-and-drop sections.</p>
</div>

<div class="card">
    <div class="card-body">
        @if($pages->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-alt text-muted" style="font-size:3rem"></i>
                <p class="mt-3 text-muted">No custom pages yet. Create your first page!</p>
                <a href="{{ route('custom-pages.create') }}" class="btn btn-primary">Create Page</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Sections</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            @php
                                $sectionCount = DB::table('custom_page_sections')->where('custom_page_id', $page->id)->count();
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $page->title }}</td>
                                <td><code>/page/{{ $page->slug }}</code></td>
                                <td>
                                    @if($page->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $sectionCount }}</td>
                                <td>{{ \Carbon\Carbon::parse($page->created_at)->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('custom-pages.builder', $page->id) }}" class="btn btn-outline-primary" title="Design"><i class="fas fa-paint-brush"></i></a>
                                        <a href="{{ route('custom-pages.edit', $page->id) }}" class="btn btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                        @if($page->status === 'published')
                                            <a href="{{ url('page/'.$page->slug) }}" class="btn btn-outline-success" target="_blank" title="Preview"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <form action="{{ route('custom-pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

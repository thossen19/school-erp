@extends('layouts.app')

@section('title', 'Design Page: ' . $page->title)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" rel="preload">
<style>
.section-card {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #fff;
    transition: box-shadow 0.2s;
}
.section-card:hover {
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.section-card .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    cursor: grab;
    user-select: none;
}
.section-card .card-header:active {
    cursor: grabbing;
}
.section-card .card-body {
    padding: 1rem;
}
.section-card.inactive {
    opacity: 0.5;
    border-style: dashed;
}
.section-placeholder {
    border: 2px dashed #adb5bd;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    color: #adb5bd;
    margin-bottom: 1rem;
}
.drag-handle {
    color: #adb5bd;
    font-size: 1.1rem;
    margin-right: 0.5rem;
}
.add-section-btn {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #6c757d;
}
.add-section-btn:hover {
    border-color: var(--bs-primary);
    color: var(--bs-primary);
    background: rgba(var(--bs-primary-rgb), 0.03);
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-0"><i class="fas fa-paint-brush me-2"></i>Design: {{ $page->title }}</h3>
            @if($page->status === 'published')
                <a href="{{ url('page/'.$page->slug) }}" target="_blank" class="text-decoration-none small">
                    <i class="fas fa-external-link-alt me-1"></i>/page/{{ $page->slug }}
                </a>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('custom-pages.edit', $page->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-cog me-1"></i>Page Settings</a>
            <a href="{{ route('custom-pages.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>All Pages</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div id="sectionList">
            @forelse($sections as $section)
                @php $content = json_decode($section->content); @endphp
                <div class="section-card {{ !$section->is_active ? 'inactive' : '' }}" data-section-id="{{ $section->id }}">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                            <span class="badge bg-secondary me-2">{{ $sectionTypes[$section->section_type] ?? $section->section_type }}</span>
                            <small class="text-muted">#{{ $section->order + 1 }}</small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary edit-section-btn" data-section-id="{{ $section->id }}" data-section-type="{{ $section->section_type }}" data-content='{{ $section->content }}' title="Edit"><i class="fas fa-pen"></i></button>
                            <a href="{{ route('custom-pages.builder.section.toggle', [$page->id, $section->id]) }}" class="btn btn-outline-{{ $section->is_active ? 'warning' : 'success' }}" title="{{ $section->is_active ? 'Hide' : 'Show' }}">
                                <i class="fas fa-{{ $section->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </a>
                            <form action="{{ route('custom-pages.builder.section.delete', [$page->id, $section->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this section?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" title="Remove"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="section-preview">
                            @if($section->section_type === 'hero')
                                <div style="background:{{ $content->background_color ?? '#1a1a2e' }};color:#fff;padding:1rem;border-radius:4px;text-align:center">
                                    <strong style="font-size:1.2rem">{{ $content->heading ?? '' }}</strong>
                                    @if(!empty($content->subheading))<p class="mb-0 small">{{ $content->subheading }}</p>@endif
                                </div>
                            @elseif($section->section_type === 'text')
                                <strong>{{ $content->title ?? '' }}</strong>
                                <div class="small text-muted mt-1">{!! Str::limit(strip_tags($content->content ?? ''), 100) !!}</div>
                            @elseif($section->section_type === 'cta')
                                <div style="background:{{ $content->background_color ?? '#BF5700' }};color:#fff;padding:1rem;border-radius:4px;text-align:center">
                                    <strong>{{ $content->heading ?? '' }}</strong>
                                    @if(!empty($content->description))<p class="mb-0 small">{{ $content->description }}</p>@endif
                                </div>
                            @elseif($section->section_type === 'features' || $section->section_type === 'cards')
                                <strong>{{ $content->title ?? '' }}</strong>
                                <p class="small text-muted mb-0">{{ count($content->items ?? $content->cards ?? []) }} item(s)</p>
                            @elseif($section->section_type === 'divider')
                                <hr style="border-color:{{ $content->color ?? '#e5e5e5' }};border-width:{{ $content->height ?? '1px' }}">
                            @elseif($section->section_type === 'html')
                                <div class="small text-muted"><em>Custom HTML block</em></div>
                            @elseif($section->section_type === 'image')
                                @if(!empty($content->image_url))
                                    <img src="{{ $content->image_url }}" alt="" style="max-height:80px;max-width:100%">
                                @else
                                    <div class="small text-muted"><em>Single Image</em></div>
                                @endif
                            @elseif($section->section_type === 'application_form')
                                <strong>{{ $content->title ?? 'Application Form' }}</strong>
                                <p class="small text-muted mb-0">Public application form with name, DOB, birth cert, phone, email, class, parents</p>
                            @else
                                <strong>{{ $content->title ?? ucfirst($section->section_type) }}</strong>
                                <p class="small text-muted mb-0">{{ $sectionTypes[$section->section_type] ?? $section->section_type }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="section-placeholder">
                    <i class="fas fa-arrow-down mb-2 d-block" style="font-size:2rem"></i>
                    <p class="mb-0">Add your first section below</p>
                </div>
            @endforelse
        </div>

        {{-- Add Section --}}
        <div class="add-section-btn" data-bs-toggle="modal" data-bs-target="#addSectionModal">
            <i class="fas fa-plus-circle mb-1 d-block" style="font-size:1.5rem"></i>
            <span class="fw-semibold">Add Section</span>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><strong>Drag & Drop Tips</strong></div>
            <div class="card-body small">
                <p class="mb-2"><i class="fas fa-grip-vertical me-1 text-muted"></i> Drag the gripper to reorder sections</p>
                <p class="mb-2"><i class="fas fa-pen me-1 text-primary"></i> Click the pen icon to edit content</p>
                <p class="mb-2"><i class="fas fa-eye-slash me-1 text-warning"></i> Toggle section visibility</p>
                <p class="mb-0"><i class="fas fa-times me-1 text-danger"></i> Remove a section</p>
                <hr>
                <p class="mb-0 text-muted">Changes are saved automatically when you drag to reorder.</p>
            </div>
        </div>
    </div>
</div>

{{-- Add Section Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('custom-pages.builder.section.store', $page->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Section Type</label>
                    <select name="section_type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        @foreach($sectionTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Section Modal --}}
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="editSectionContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSectionBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(function() {
    // Drag & Drop reorder
    var sectionList = document.getElementById('sectionList');
    if (sectionList) {
        Sortable.create(sectionList, {
            handle: '.card-header',
            animation: 150,
            ghostClass: 'section-placeholder',
            onEnd: function() {
                var ids = [];
                $('#sectionList .section-card').each(function() {
                    ids.push($(this).data('section-id'));
                });
                $.ajax({
                    url: '{{ route("custom-pages.builder.reorder", $page->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        section_ids: ids
                    },
                    success: function() {
                        // Update order numbers
                        $('#sectionList .section-card').each(function(i) {
                            $(this).find('.card-header small.text-muted').text('#' + (i + 1));
                        });
                    }
                });
            }
        });
    }

    // Edit section - open modal with dynamic form
    $(document).on('click', '.edit-section-btn', function() {
        var sectionId = $(this).data('section-id');
        var sectionType = $(this).data('section-type');
        var content = $(this).data('content');

        if (typeof content === 'string') {
            try { content = JSON.parse(content); } catch(e) {}
        }

        var html = '';
        html += '<input type="hidden" id="editSectionId" value="' + sectionId + '">';

        if (sectionType === 'hero') {
            html += '<div class="mb-3"><label class="form-label">Heading</label><input type="text" class="form-control edit-field" data-key="heading" value="' + escHtml(content.heading || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Subheading</label><textarea class="form-control edit-field" data-key="subheading">' + escHtml(content.subheading || '') + '</textarea></div>';
            html += '<div class="mb-3"><label class="form-label">Button Text</label><input type="text" class="form-control edit-field" data-key="button_text" value="' + escHtml(content.button_text || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Button URL</label><input type="text" class="form-control edit-field" data-key="button_url" value="' + escHtml(content.button_url || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Background Color</label><input type="color" class="form-control form-control-color edit-field" data-key="background_color" value="' + (content.background_color || '#1a1a2e') + '"></div>';
        } else if (sectionType === 'text') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Content (HTML)</label><textarea class="form-control edit-field" data-key="content" rows="6">' + escHtml(content.content || '') + '</textarea></div>';
        } else if (sectionType === 'features') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Items (JSON array of {icon, title, description})</label><textarea class="form-control edit-field" data-key="items" rows="6">' + escHtml(JSON.stringify(content.items || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'cards') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Cards (JSON array of {title, content})</label><textarea class="form-control edit-field" data-key="cards" rows="6">' + escHtml(JSON.stringify(content.cards || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'cta') {
            html += '<div class="mb-3"><label class="form-label">Heading</label><input type="text" class="form-control edit-field" data-key="heading" value="' + escHtml(content.heading || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control edit-field" data-key="description">' + escHtml(content.description || '') + '</textarea></div>';
            html += '<div class="mb-3"><label class="form-label">Button Text</label><input type="text" class="form-control edit-field" data-key="button_text" value="' + escHtml(content.button_text || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Button URL</label><input type="text" class="form-control edit-field" data-key="button_url" value="' + escHtml(content.button_url || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Background Color</label><input type="color" class="form-control form-control-color edit-field" data-key="background_color" value="' + (content.background_color || '#BF5700') + '"></div>';
        } else if (sectionType === 'image_text') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Content (HTML)</label><textarea class="form-control edit-field" data-key="content" rows="4">' + escHtml(content.content || '') + '</textarea></div>';
            html += '<div class="mb-3"><label class="form-label">Image</label>' + imageUploader('image_url', content.image_url || '') + '</div>';
            html += '<div class="mb-3"><label class="form-label">Image Position</label><select class="form-select edit-field" data-key="image_position"><option value="left" ' + (content.image_position === 'left' ? 'selected' : '') + '>Left</option><option value="center" ' + (content.image_position === 'center' ? 'selected' : '') + '>Center</option><option value="right" ' + (content.image_position === 'right' ? 'selected' : '') + '>Right</option></select></div>';
            html += '<div class="mb-3"><label class="form-label">Image Size</label><select class="form-select edit-field" data-key="image_size"><option value="full_width" ' + ((content.image_size || 'full_width') === 'full_width' ? 'selected' : '') + '>Full width</option><option value="original" ' + (content.image_size === 'original' ? 'selected' : '') + '>Original Size</option><option value="half_width" ' + (content.image_size === 'half_width' ? 'selected' : '') + '>50% of full width</option></select></div>';
        } else if (sectionType === 'faq') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">FAQ Items (JSON array of {question, answer})</label><textarea class="form-control edit-field" data-key="items" rows="6">' + escHtml(JSON.stringify(content.items || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'contact') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Email</label><input type="text" class="form-control edit-field" data-key="email" value="' + escHtml(content.email || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control edit-field" data-key="phone" value="' + escHtml(content.phone || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Address</label><textarea class="form-control edit-field" data-key="address">' + escHtml(content.address || '') + '</textarea></div>';
        } else if (sectionType === 'image') {
            html += '<div class="mb-3"><label class="form-label">Image</label>' + imageUploader('image_url', content.image_url || '') + '</div>';
            html += '<div class="mb-3"><label class="form-label">Alt Text</label><input type="text" class="form-control edit-field" data-key="alt_text" value="' + escHtml(content.alt_text || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Caption</label><input type="text" class="form-control edit-field" data-key="caption" value="' + escHtml(content.caption || '') + '"></div>';
        } else if (sectionType === 'pricing') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Plans (JSON array of {name, price, features})</label><textarea class="form-control edit-field" data-key="plans" rows="6">' + escHtml(JSON.stringify(content.plans || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'team') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Members (JSON array of {name, designation, image_url})</label><textarea class="form-control edit-field" data-key="members" rows="6">' + escHtml(JSON.stringify(content.members || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'testimonials') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Items (JSON array of {name, text, rating})</label><textarea class="form-control edit-field" data-key="items" rows="6">' + escHtml(JSON.stringify(content.items || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'stats') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Items (JSON array of {label, value})</label><textarea class="form-control edit-field" data-key="items" rows="6">' + escHtml(JSON.stringify(content.items || [], null, 2)) + '</textarea></div>';
        } else if (sectionType === 'gallery') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Image URLs (one per line)</label><textarea class="form-control edit-field" data-key="images" rows="4">' + escHtml((content.images || []).join('\n')) + '</textarea></div>';
        } else if (sectionType === 'video') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Video URL</label><input type="text" class="form-control edit-field" data-key="url" value="' + escHtml(content.url || '') + '"></div>';
        } else if (sectionType === 'newsletter') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Placeholder</label><input type="text" class="form-control edit-field" data-key="placeholder" value="' + escHtml(content.placeholder || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Button Text</label><input type="text" class="form-control edit-field" data-key="button_text" value="' + escHtml(content.button_text || '') + '"></div>';
        } else if (sectionType === 'partners') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Logo URLs (one per line)</label><textarea class="form-control edit-field" data-key="logos" rows="4">' + escHtml((content.logos || []).join('\n')) + '</textarea></div>';
        } else if (sectionType === 'divider') {
            html += '<div class="mb-3"><label class="form-label">Color</label><input type="color" class="form-control form-control-color edit-field" data-key="color" value="' + (content.color || '#e5e5e5') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Height</label><input type="text" class="form-control edit-field" data-key="height" value="' + (content.height || '1px') + '"></div>';
        } else if (sectionType === 'html') {
            html += '<div class="mb-3"><label class="form-label">Custom HTML</label><textarea class="form-control edit-field" data-key="html" rows="8">' + escHtml(content.html || '') + '</textarea></div>';
        } else if (sectionType === 'application_form') {
            html += '<div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control edit-field" data-key="title" value="' + escHtml(content.title || '') + '"></div>';
            html += '<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control edit-field" data-key="description" rows="2">' + escHtml(content.description || '') + '</textarea></div>';
            html += '<div class="mb-3"><label class="form-label">Submit Button Text</label><input type="text" class="form-control edit-field" data-key="button_text" value="' + escHtml(content.button_text || '') + '"></div>';
        } else {
            html += '<p class="text-muted">No editable fields for this section type.</p>';
        }

        $('#editSectionContent').html(html);
        $('#editSectionModal').modal('show');
    });

    // Save section edits
    $('#saveSectionBtn').click(function() {
        var sectionId = $('#editSectionId').val();
        var data = {};
        $('.edit-field').each(function() {
            var key = $(this).data('key');
            var val = $(this).val();
            // Try to parse JSON fields
            if ($(this).is('textarea') && (key === 'items' || key === 'cards' || key === 'plans' || key === 'members' || key === 'logos')) {
                try { val = JSON.parse(val); } catch(e) {}
            }
            if (key === 'images') {
                val = val.split('\n').filter(function(v) { return v.trim(); });
            }
            data[key] = val;
        });

        $.ajax({
            url: '{{ route("custom-pages.builder.section.update", [$page->id, "SECTION_ID"]) }}'.replace('SECTION_ID', sectionId),
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                content: JSON.stringify(data)
            },
            success: function() {
                $('#editSectionModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Error saving section. Check your JSON syntax.');
            }
        });
    });

    function imageUploader(key, currentUrl) {
        var previewStyle = currentUrl ? 'max-height:120px;border-radius:4px' : 'display:none';
        return '<div class="image-upload-wrap mb-1">' +
            '<div class="mb-2 text-center"><img src="' + escHtml(currentUrl) + '" class="img-preview-' + key + '" style="' + previewStyle + '"></div>' +
            '<div class="input-group input-group-sm">' +
            '<input type="text" class="form-control edit-field" data-key="' + key + '" value="' + escHtml(currentUrl) + '" placeholder="Image URL">' +
            '<button class="btn btn-outline-primary upload-btn" type="button" data-key="' + key + '" title="Upload from computer"><i class="fas fa-cloud-upload-alt"></i></button>' +
            '</div>' +
            '<input type="file" accept="image/*" class="d-none file-input" data-key="' + key + '">' +
            '<small class="text-muted">Paste URL or click upload button to choose a file</small>' +
            '</div>';
    }

    // Image upload handler
    $(document).on('click', '.upload-btn', function() {
        $(this).closest('.image-upload-wrap').find('.file-input').click();
    });

    $(document).on('change', '.file-input', function() {
        var key = $(this).data('key');
        var file = this.files[0];
        if (!file) return;
        var formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        var btn = $(this).closest('.image-upload-wrap').find('.upload-btn');
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            url: '{{ route("custom-pages.upload-image") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    var wrap = btn.closest('.image-upload-wrap');
                    wrap.find('.edit-field[data-key="' + key + '"]').val(res.url);
                    wrap.find('.img-preview-' + key).attr('src', res.url).show();
                }
                btn.html('<i class="fas fa-cloud-upload-alt"></i>');
            },
            error: function() {
                alert('Upload failed. Check file size (max 5MB) and type.');
                btn.html('<i class="fas fa-cloud-upload-alt"></i>');
            }
        });
    });

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
});
</script>
@endpush

@extends('layouts.app')
@section('title', 'Menu Manage')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-bars me-2"></i>Menu Manage</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li>
                <li class="breadcrumb-item active">Menu Manage</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal"><i class="fas fa-plus me-1"></i>Add Menu Item</button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<ul class="nav nav-tabs mb-3" id="menuTabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" id="utility_bar-tab" data-bs-toggle="tab" data-bs-target="#utility_bar" type="button" role="tab"><i class="fas fa-chevron-circle-up me-1"></i>Top Utility Bar</button></li>
    <li class="nav-item"><button class="nav-link" id="header-tab" data-bs-toggle="tab" data-bs-target="#header" type="button" role="tab"><i class="fas fa-window-restore me-1"></i>Header Navigation</button></li>
    <li class="nav-item"><button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab"><i class="fas fa-th-large me-1"></i>Footer Columns</button></li>
    <li class="nav-item"><button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab"><i class="fas fa-share-alt me-1"></i>Social Media</button></li>
</ul>

<div class="tab-content">
    @foreach(['utility_bar' => 'Top Utility Bar', 'header' => 'Header Navigation', 'footer' => 'Footer Columns', 'social' => 'Social Media'] as $type => $label)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $type }}" role="tabpanel">

        {{-- Preview Card --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-eye me-1 text-muted"></i>Preview</h6>
                <small class="text-muted">How this menu appears on the homepage</small>
            </div>
            <div class="card-body">
                @if($type === 'utility_bar')
                <div style="background:#333F48;padding:0.5rem 1rem;border-radius:4px;font-size:0.8rem;">
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        @forelse($grouped[$type] as $item)
                            <span style="color:rgba(255,255,255,0.8);"><i class="fas {{ $item->icon ?? 'fa-link' }} me-1" style="color:#BF5700;"></i>{{ $item->label }}</span>
                        @empty
                            <span class="text-white-50">No utility bar items</span>
                        @endforelse
                    </div>
                </div>
                @elseif($type === 'header')
                <nav class="navbar navbar-expand-lg" style="border-bottom:1px solid #E5E5E5;padding:0;">
                    <div class="d-flex w-100 justify-content-between align-items-center" style="padding:0.75rem 1rem;">
                        <span style="font-family:'Playfair Display',serif;font-weight:900;font-size:1.2rem;color:#333F48;">AISchool <small style="font-size:0.55rem;display:block;color:#BF5700;text-transform:uppercase;letter-spacing:2px;">What Starts Here</small></span>
                        <div class="d-flex gap-3">
                            @forelse($grouped[$type] as $item)
                                <span style="font-size:0.85rem;font-weight:500;color:#333F48;padding:0.25rem 0;border-bottom:3px solid {{ $loop->first ? '#BF5700' : 'transparent' }};">{{ $item->label }}</span>
                            @empty
                                <span class="text-muted small">No header items</span>
                            @endforelse
                        </div>
                        <span class="btn btn-sm" style="background:#BF5700;color:#fff;border-radius:0;padding:0.3rem 1rem;font-size:0.8rem;">Log In</span>
                    </div>
                </nav>
                @elseif($type === 'footer')
                <div style="background:#1a1a2e;padding:1.5rem;border-radius:4px;">
                    <div class="row g-3">
                        @forelse($grouped[$type] as $col)
                        <div class="col-3">
                            <div style="color:#fff;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">{{ $col->label }}</div>
                            @if(isset($col->children))
                                @foreach($col->children as $link)
                                    <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;padding:0.15rem 0;">{{ $link->label }}</div>
                                @endforeach
                            @endif
                        </div>
                        @empty
                            <div class="col-12"><span class="text-white-50 small">No footer columns</span></div>
                        @endforelse
                    </div>
                </div>
                @elseif($type === 'social')
                <div class="text-center">
                    @forelse($grouped[$type] as $item)
                        <span class="d-inline-block mx-2" style="font-size:1.5rem;color:#333F48;"><i class="{{ $item->icon ?? 'fas fa-link' }}"></i></span>
                    @empty
                        <span class="text-muted small">No social media links</span>
                    @endforelse
                </div>
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">#</th>
                                <th>Label</th>
                                <th>URL</th>
                                @if($type === 'social')<th>Icon</th>@endif
                                @if($type === 'footer')<th>Column</th>@endif
                                <th style="width:80px">Status</th>
                                <th style="width:100px" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $tree = $grouped[$type] ?? []; @endphp
                            @if(empty($tree))
                                <tr><td colspan="{{ $type === 'footer' ? 6 : ($type === 'social' ? 6 : 5) }}" class="text-muted text-center py-4"><i class="fas fa-inbox me-2"></i>No {{ strtolower($label) }} items yet. Click "Add Menu Item" to create one.</td></tr>
                            @else
                                @foreach($tree as $item)
                                    @include('menu-manage._row', ['item' => $item, 'type' => $type, 'depth' => 0])
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('menu-manage.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Menu Type</label>
                            <select name="menu_type" class="form-select" required>
                                <option value="utility_bar">Top Utility Bar</option>
                                <option value="header">Header Navigation</option>
                                <option value="footer">Footer Columns</option>
                                <option value="social">Social Media</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Label</label>
                            <input type="text" name="label" class="form-control" required placeholder="e.g. About Us">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">URL</label>
                            <input type="text" name="url" class="form-control" placeholder="/about or https://example.com">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Icon (FontAwesome)</label>
                            <input type="text" name="icon" class="form-control" placeholder="fa-users">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Target</label>
                            <select name="target" class="form-select">
                                <option value="_self">Same Tab</option>
                                <option value="_blank">New Tab</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Parent Item</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                @foreach(DB::table('menu_items')->where('school_id', 1)->whereNull('parent_id')->orderBy('label')->get() as $mi)
                                    <option value="{{ $mi->id }}">{{ $mi->label }} ({{ ucwords(str_replace('_',' ',$mi->menu_type)) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Order</label>
                            <input type="number" name="order" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Footer Column</label>
                            <select name="footer_column" class="form-select">
                                <option value="">None</option>
                                <option value="1">Column 1</option>
                                <option value="2">Column 2</option>
                                <option value="3">Column 3</option>
                                <option value="4">Column 4</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">CSS Class</label>
                            <input type="text" name="css_class" class="form-control" placeholder="nav-link custom">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Permissions (comma-separated roles)</label>
                            <input type="text" name="permissions" class="form-control" placeholder="super_admin, school_admin">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked id="addActive">
                                <label class="form-check-label" for="addActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modals --}}
@foreach(['utility_bar', 'header', 'footer', 'social'] as $type)
    @foreach($grouped[$type] ?? [] as $item)
        @include('menu-manage._edit_modal', ['item' => $item, 'type' => $type])
        @if(isset($item->children))
            @foreach($item->children as $child)
                @include('menu-manage._edit_modal', ['item' => $child, 'type' => $type])
            @endforeach
        @endif
    @endforeach
@endforeach

{{-- Delete Forms --}}
@foreach(['utility_bar', 'header', 'footer', 'social'] as $type)
    @foreach($grouped[$type] ?? [] as $item)
        <form method="POST" action="{{ route('menu-manage.destroy', $item->id) }}" id="deleteForm{{ $item->id }}" class="d-none">@csrf @method('DELETE')</form>
        @if(isset($item->children))
            @foreach($item->children as $child)
                <form method="POST" action="{{ route('menu-manage.destroy', $child->id) }}" id="deleteForm{{ $child->id }}" class="d-none">@csrf @method('DELETE')</form>
            @endforeach
        @endif
    @endforeach
@endforeach
@endsection

@push('scripts')
<script>
$(function(){
    $('.btn-delete').click(function(){
        if(confirm('Delete this menu item?')) {
            $('#' + $(this).data('form')).submit();
        }
    });
    $('.btn-edit').click(function(){
        var id = $(this).data('id');
        new bootstrap.Modal(document.getElementById('editMenuModal' + id)).show();
    });
    // Change icons based on menu type in add modal
    $('[name="menu_type"]').change(function(){
        var v = $(this).val();
        var iconField = $('[name="icon"]');
        var footerCol = $('[name="footer_column"]').closest('.col-md-3');
        var cssClass = $('[name="css_class"]').closest('.col-md-3');
        if(v === 'social') {
            iconField.attr('placeholder', 'fab fa-facebook-f');
            footerCol.hide();
            cssClass.hide();
        } else if(v === 'footer') {
            iconField.attr('placeholder', 'fa-link');
            footerCol.show();
            cssClass.show();
        } else if(v === 'utility_bar') {
            iconField.attr('placeholder', 'fa-map-marker-alt');
            footerCol.hide();
            cssClass.show();
        } else {
            iconField.attr('placeholder', 'fa-link');
            footerCol.hide();
            cssClass.show();
        }
    }).trigger('change');
});
</script>
@endpush
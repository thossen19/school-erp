<div class="modal fade" id="editMenuModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('menu-manage.update', $item->id) }}">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit: {{ $item->label }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Menu Type</label>
                            <select name="menu_type" class="form-select" required>
                                <option value="utility_bar" {{ $item->menu_type == 'utility_bar' ? 'selected' : '' }}>Top Utility Bar</option>
                                <option value="header" {{ $item->menu_type == 'header' ? 'selected' : '' }}>Header Navigation</option>
                                <option value="footer" {{ $item->menu_type == 'footer' ? 'selected' : '' }}>Footer Columns</option>
                                <option value="social" {{ $item->menu_type == 'social' ? 'selected' : '' }}>Social Media</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Label</label>
                            <input type="text" name="label" class="form-control" required value="{{ $item->label }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">URL</label>
                            <input type="text" name="url" class="form-control" value="{{ $item->url }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Icon</label>
                            <input type="text" name="icon" class="form-control" value="{{ $item->icon }}" placeholder="fab fa-facebook">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Target</label>
                            <select name="target" class="form-select">
                                <option value="_self" {{ ($item->target ?? '_self') == '_self' ? 'selected' : '' }}>Same Tab</option>
                                <option value="_blank" {{ ($item->target ?? '') == '_blank' ? 'selected' : '' }}>New Tab</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Parent Item</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                @foreach(DB::table('menu_items')->where('school_id', 1)->whereNull('parent_id')->orderBy('label')->get() as $mi)
                                    <option value="{{ $mi->id }}" {{ $item->parent_id == $mi->id ? 'selected' : '' }}>{{ $mi->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Order</label>
                            <input type="number" name="order" class="form-control" value="{{ $item->order ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Footer Column</label>
                            <select name="footer_column" class="form-select">
                                <option value="">None</option>
                                @foreach([1,2,3,4] as $c)
                                    <option value="{{ $c }}" {{ ($item->mega_columns ?? '') == $c ? 'selected' : '' }}>Column {{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">CSS Class</label>
                            <input type="text" name="css_class" class="form-control" value="{{ $item->css_class }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Permissions</label>
                            <input type="text" name="permissions" class="form-control" value="{{ $item->permissions }}">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $item->is_active ? 'checked' : '' }} id="editActive{{ $item->id }}">
                                <label class="form-check-label" for="editActive{{ $item->id }}">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
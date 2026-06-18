<tr>
    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $item->order }}</span></td>
    <td>
        <span style="margin-left: {{ $depth * 20 }}px">
            @if($depth > 0)<span class="text-muted me-1">└─</span>@endif
            @if($item->icon)<i class="{{ $item->icon }} me-1 text-primary"></i>@endif
            <span class="fw-semibold">{{ $item->label }}</span>
        </span>
    </td>
    <td><code class="small text-muted">{{ $item->url ?? '-' }}</code></td>
    @if($type === 'social')<td><code class="small">{{ $item->icon ?? '-' }}</code></td>@endif
    @if($type === 'footer')
    <td>
        @if($item->parent_id)
            <span class="text-muted small">Sub-item</span>
        @else
            <span class="badge bg-info bg-opacity-10 text-info">Column {{ $item->mega_columns ?? '?' }}</span>
        @endif
    </td>
    @endif
    <td>
        <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }} bg-opacity-10 text-{{ $item->is_active ? 'success' : 'danger' }}">
            {{ $item->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-primary btn-edit" data-id="{{ $item->id }}" title="Edit"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-outline-danger btn-delete" data-form="deleteForm{{ $item->id }}" title="Delete"><i class="fas fa-trash"></i></button>
    </td>
</tr>
@if(isset($item->children))
    @foreach($item->children as $child)
        @include('menu-manage._row', ['item' => $child, 'type' => $type, 'depth' => $depth + 1])
    @endforeach
@endif
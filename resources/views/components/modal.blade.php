@props(['id', 'title' => '', 'size' => '', 'static' => false])
<div class="modal fade" id="{{ $id }}" tabindex="-1" {{ $static ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : '' }}>
    <div class="modal-dialog {{ $size ? 'modal-' . $size : '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{!! $title !!}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
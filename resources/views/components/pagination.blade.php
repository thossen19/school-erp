@props(['paginator'])
@if ($paginator->hasPages())
<div {{ $attributes->merge(['class' => 'd-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-3 bg-white border-top']) }}>
    <small class="text-muted lh-sm">
        <i class="fas fa-list-ul me-1"></i>Showing
        <span class="fw-semibold text-dark">{{ $paginator->firstItem() ?? 0 }}</span>
        to <span class="fw-semibold text-dark">{{ $paginator->lastItem() ?? 0 }}</span>
        of <span class="fw-semibold text-dark">{{ $paginator->total() }}</span>
        records
    </small>
    {{ $paginator->links() }}
</div>
@endif

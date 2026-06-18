<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['paginator']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php if($paginator->hasPages()): ?>
<div <?php echo e($attributes->merge(['class' => 'd-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-3 bg-white border-top'])); ?>>
    <small class="text-muted lh-sm">
        <i class="fas fa-list-ul me-1"></i>Showing
        <span class="fw-semibold text-dark"><?php echo e($paginator->firstItem() ?? 0); ?></span>
        to <span class="fw-semibold text-dark"><?php echo e($paginator->lastItem() ?? 0); ?></span>
        of <span class="fw-semibold text-dark"><?php echo e($paginator->total()); ?></span>
        records
    </small>
    <?php echo e($paginator->links()); ?>

</div>
<?php endif; ?>
<?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/components/pagination.blade.php ENDPATH**/ ?>
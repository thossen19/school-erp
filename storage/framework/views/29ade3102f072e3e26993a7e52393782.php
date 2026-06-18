<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'title' => '', 'size' => '', 'static' => false]));

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

foreach (array_filter((['id', 'title' => '', 'size' => '', 'static' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="modal fade" id="<?php echo e($id); ?>" tabindex="-1" <?php echo e($static ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : ''); ?>>
    <div class="modal-dialog <?php echo e($size ? 'modal-' . $size : ''); ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $title; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php echo e($slot); ?>

            </div>
            <?php if(isset($footer)): ?>
                <div class="modal-footer">
                    <?php echo e($footer); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/components/modal.blade.php ENDPATH**/ ?>
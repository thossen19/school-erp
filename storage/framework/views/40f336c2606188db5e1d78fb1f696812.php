<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['headers' => [], 'striped' => true, 'hover' => true, 'responsive' => true]));

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

foreach (array_filter((['headers' => [], 'striped' => true, 'hover' => true, 'responsive' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="<?php echo e($responsive ? 'table-responsive' : ''); ?>">
    <table class="table <?php echo e($striped ? 'table-striped' : ''); ?> <?php echo e($hover ? 'table-hover' : ''); ?> align-middle mb-0" <?php echo e($attributes); ?>>
        <?php if(count($headers) > 0): ?>
            <thead class="table-light">
                <tr>
                    <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th scope="col"><?php echo e($header); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
        <?php endif; ?>
        <tbody>
            <?php echo e($slot); ?>

        </tbody>
    </table>
</div><?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/components/table.blade.php ENDPATH**/ ?>
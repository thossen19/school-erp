<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">
                    <?php if($school->logo ?? false): ?>
                        <img src="<?php echo e(asset('storage/'.$school->logo)); ?>" alt="<?php echo e($school->name); ?>" height="32">
                    <?php else: ?>
                        <?php echo e(config('app.name', 'AISchool')); ?>

                        <small>What Starts Here</small>
                    <?php endif; ?>
                </div>
                <p style="color:rgba(255,255,255,0.5);font-size:0.85rem;line-height:1.7;">
                    Excellence in education since 2000. We prepare students to thrive in a rapidly changing world 
                    through a commitment to academic rigor, character development, and community engagement.
                </p>
                <div class="social mt-3">
                    <?php $__currentLoopData = $socialItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($sItem->url ?: '#'); ?>" aria-label="<?php echo e($sItem->label); ?>"><i class="<?php echo e($sItem->icon ?: 'fas fa-link'); ?>"></i></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php $__currentLoopData = $footerColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-lg-2">
                <h6><?php echo e($col->label); ?></h6>
                <?php $__currentLoopData = $footerChildren->where('parent_id', $col->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($link->url ?: '#'); ?>"><?php echo e($link->label); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
            <span>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'AISchool')); ?>. All rights reserved.</span>
            <span class="mt-2 mt-md-0">
                <i class="fas fa-heart" style="color:var(--burnt-orange)"></i> What Starts Here Changes Everything
            </span>
        </div>
    </div>
</footer>
<?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/partials/homepage-footer.blade.php ENDPATH**/ ?>
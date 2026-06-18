<div class="utility-bar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <?php $__currentLoopData = $utilityItems->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($item->url ?: '#'); ?>"><i class="fas <?php echo e($item->icon ?: 'fa-link'); ?> me-1"></i><?php echo e($item->label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div>
            <?php $__currentLoopData = $utilityItems->skip(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($item->url ?: '#'); ?>"><i class="fas <?php echo e($item->icon ?: 'fa-link'); ?> me-1"></i><?php echo e($item->label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<nav class="main-nav navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <?php if($school->logo ?? false): ?>
                <img src="<?php echo e(asset('storage/'.$school->logo)); ?>" alt="<?php echo e($school->name); ?>" height="80">
            <?php else: ?>
                <?php echo e(config('app.name', 'AISchool')); ?>

                <small>What Starts Here</small>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <?php $__currentLoopData = $headerItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $subItems = $headerChildren->where('parent_id', $hItem->id);
                        $itemPath = trim(parse_url($hItem->url ?: '', PHP_URL_PATH) ?: $hItem->url ?: '', '/');
                        $isActive = $hItem->url && $hItem->url !== '#' && request()->path() === $itemPath;
                        if (!$isActive && $subItems->isNotEmpty()) {
                            foreach ($subItems as $child) {
                                $childPath = trim(parse_url($child->url ?: '', PHP_URL_PATH) ?: $child->url ?: '', '/');
                                if (request()->path() === $childPath) { $isActive = true; break; }
                            }
                        }
                    ?>
                    <?php if($subItems->isNotEmpty()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo e($isActive ? 'active' : ''); ?>" href="<?php echo e($hItem->url ?: '#'); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if($hItem->icon): ?><i class="fas <?php echo e($hItem->icon); ?> me-1"></i><?php endif; ?><?php echo e($hItem->label); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <?php $__currentLoopData = $subItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $childActive = request()->path() === trim(parse_url($child->url ?: '', PHP_URL_PATH) ?: $child->url ?: '', '/'); ?>
                            <li><a class="dropdown-item <?php echo e($childActive ? 'active' : ''); ?>" href="<?php echo e($child->url ?: '#'); ?>"><?php if($child->icon): ?><i class="fas <?php echo e($child->icon); ?> me-1"></i><?php endif; ?><?php echo e($child->label); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e($isActive ? 'active' : ''); ?>" href="<?php echo e($hItem->url ?: '#'); ?>">
                            <?php if($hItem->icon): ?><i class="fas <?php echo e($hItem->icon); ?> me-1"></i><?php endif; ?><?php echo e($hItem->label); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <?php if(auth()->guard()->check()): ?>
            <div class="ms-3">
                <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-orange"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/partials/homepage-header.blade.php ENDPATH**/ ?>
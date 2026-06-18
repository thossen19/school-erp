<div class="container-xl">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-labelledby="system-breadcrumb" class="d-inline-block">
                <h2 id="system-breadcrumb" class="visually-hidden">Breadcrumb</h2>
                <ol class="breadcrumb border system-breadcrumb">
                    <?php if(request()->path() === '/'): ?>
                    <li class="breadcrumb-item" aria-current="page">Home</li>
                    <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(url('/')); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" style="color:#1f262b">
                        <?php echo e($breadcrumbTitle ?? ($page->title ?? '')); ?>

                    </li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/partials/public-breadcrumb.blade.php ENDPATH**/ ?>
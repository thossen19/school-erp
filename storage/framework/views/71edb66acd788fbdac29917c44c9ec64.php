<?php $__env->startSection('title', 'Student Performance Prediction'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>Student Performance Prediction</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Performance Prediction</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-chart-simple','value' => ''.e(number_format($stats->avg_score ?? 0, 1)).'%','title' => 'Average Score','color' => 'primary'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatsCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcdff021ff5f0998733708169e37bbb75)): ?>
<?php $attributes = $__attributesOriginalcdff021ff5f0998733708169e37bbb75; ?>
<?php unset($__attributesOriginalcdff021ff5f0998733708169e37bbb75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcdff021ff5f0998733708169e37bbb75)): ?>
<?php $component = $__componentOriginalcdff021ff5f0998733708169e37bbb75; ?>
<?php unset($__componentOriginalcdff021ff5f0998733708169e37bbb75); ?>
<?php endif; ?>
    </div>
    <div class="col-md-3">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-calculator','value' => ''.e($stats->total ?? 0).'','title' => 'Total Predictions','color' => 'info'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatsCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcdff021ff5f0998733708169e37bbb75)): ?>
<?php $attributes = $__attributesOriginalcdff021ff5f0998733708169e37bbb75; ?>
<?php unset($__attributesOriginalcdff021ff5f0998733708169e37bbb75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcdff021ff5f0998733708169e37bbb75)): ?>
<?php $component = $__componentOriginalcdff021ff5f0998733708169e37bbb75; ?>
<?php unset($__componentOriginalcdff021ff5f0998733708169e37bbb75); ?>
<?php endif; ?>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#perfSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Filter by Class <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="perfSearchCollapse">
            <form method="GET" action="<?php echo e(route('ai.performance-prediction')); ?>" class="row g-2">
                <div class="col-md-3">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(request('class_id')==$c->id?'selected':''); ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button>
                    <a href="<?php echo e(route('ai.performance-prediction')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headers' => ['#','Student','Admission No','Prediction Score','Label','Predicted At']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['#','Student','Admission No','Prediction Score','Label','Predicted At'])]); ?>
            <?php $__empty_1 = true; $__currentLoopData = $predictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($loop->iteration + ($predictions->currentPage()-1)*$predictions->perPage()); ?></td>
                <td class="fw-semibold"><?php echo e($p->student_name ?? $p->student->name ?? '-'); ?></td>
                <td><?php echo e($p->admission_no ?? $p->student->admission_no ?? '-'); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($p->score >= 70 ? 'success' : ($p->score >= 40 ? 'warning' : 'danger')); ?>">
                        <?php echo e($p->score ?? $p->prediction_score ?? 0); ?>%
                    </span>
                </td>
                <td><?php echo e($p->label ?? '-'); ?></td>
                <td><?php echo e($p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('M d, Y H:i') : '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No predictions found</td></tr>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
    </div>
</div>
<?php if (isset($component)) { $__componentOriginal41032d87daf360242eb88dbda6c75ed1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41032d87daf360242eb88dbda6c75ed1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination','data' => ['paginator' => $predictions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($predictions)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41032d87daf360242eb88dbda6c75ed1)): ?>
<?php $attributes = $__attributesOriginal41032d87daf360242eb88dbda6c75ed1; ?>
<?php unset($__attributesOriginal41032d87daf360242eb88dbda6c75ed1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41032d87daf360242eb88dbda6c75ed1)): ?>
<?php $component = $__componentOriginal41032d87daf360242eb88dbda6c75ed1; ?>
<?php unset($__componentOriginal41032d87daf360242eb88dbda6c75ed1); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/ai/performance-prediction.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'AI Analytics Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>AI Analytics Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Analytics Dashboard</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-users','value' => ''.e($totalStudents ?? 0).'','title' => 'Total Students','color' => 'primary'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="col-xl-3 col-md-6">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-chalkboard-user','value' => ''.e($totalTeachers ?? 0).'','title' => 'Total Teachers','color' => 'success'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="col-xl-3 col-md-6">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-calendar-check','value' => ''.e($avgAttendance ?? 0).'%','title' => 'Avg Attendance','color' => 'info'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="col-xl-3 col-md-6">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-coins','value' => '৳'.e(number_format($feeCollection ?? 0, 2)).'','title' => 'Fee Collection','color' => 'warning'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<div class="row g-3">
    <div class="col-xl-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-robot me-2"></i>AI Predictions Overview</h6></div>
            <div class="card-body p-0">
                <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headers' => ['Prediction Type','Average Score','Total']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Prediction Type','Average Score','Total'])]); ?>
                    <?php $__empty_1 = true; $__currentLoopData = $predictions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $p->type ?? $p['type'] ?? 'N/A'))); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e(($p->avg_score ?? $p['avg_score'] ?? 0) >= 70 ? 'success' : (($p->avg_score ?? $p['avg_score'] ?? 0) >= 40 ? 'warning' : 'danger')); ?>">
                                <?php echo e(number_format($p->avg_score ?? $p['avg_score'] ?? 0, 1)); ?>%
                            </span>
                        </td>
                        <td><?php echo e($p->total ?? $p['total'] ?? 0); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">No predictions available</td></tr>
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
    </div>
    <div class="col-xl-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2"></i>Recent AI Reports</h6></div>
            <div class="card-body p-0">
                <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headers' => ['Title','Type','Status','Generated At']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Title','Type','Status','Generated At'])]); ?>
                    <?php $__empty_1 = true; $__currentLoopData = $recentReports ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($r->title ?? $r['title'] ?? 'Untitled'); ?></td>
                        <td><span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $r->type ?? $r['type'] ?? 'custom'))); ?></span></td>
                        <td>
                            <?php
                                $s = $r->status ?? $r['status'] ?? 'pending';
                                $sc = $s == 'completed' ? 'success' : ($s == 'processing' ? 'warning' : 'secondary');
                            ?>
                            <span class="badge bg-<?php echo e($sc); ?>"><?php echo e(ucfirst($s)); ?></span>
                        </td>
                        <td><?php echo e(\Carbon\Carbon::parse($r->created_at ?? $r['created_at'] ?? now())->format('M d, Y H:i')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No reports yet</td></tr>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/ai/analytics-dashboard.blade.php ENDPATH**/ ?>
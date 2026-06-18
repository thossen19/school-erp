<?php $__env->startSection('title', 'AI Timetable Generator'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-table me-2"></i>AI Timetable Generator</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Timetable Generator</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-school','value' => ''.e($classes->count() ?? 0).'','title' => 'Total Classes','color' => 'primary'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="col-md-4">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-chalkboard-user','value' => ''.e($teachers->count() ?? 0).'','title' => 'Available Teachers','color' => 'success'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="col-md-4">
        <?php if (isset($component)) { $__componentOriginalcdff021ff5f0998733708169e37bbb75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcdff021ff5f0998733708169e37bbb75 = $attributes; } ?>
<?php $component = App\View\Components\StatsCard::resolve(['icon' => 'fa-list','value' => ''.e($existing ?? 0).'','title' => 'Existing Timetable Entries','color' => 'info'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-wand-magic-sparkles me-2"></i>Generate Timetable</h6></div>
    <div class="card-body">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('ai.timetable-generator.store')); ?>" class="row g-2 align-items-end">
            <?php echo csrf_field(); ?>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Select Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-magic me-1"></i>Generate with AI
                </button>
            </div>
        </form>

        <hr>
        <h6 class="fw-semibold mb-2">Available Resources</h6>
        <div class="row g-2">
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Classes</small>
                    <h5 class="fw-bold mb-0"><?php echo e($classes->count()); ?></h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Teachers</small>
                    <h5 class="fw-bold mb-0"><?php echo e($teachers->count()); ?></h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Existing Entries</small>
                    <h5 class="fw-bold mb-0"><?php echo e($existing); ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/ai/timetable-generator.blade.php ENDPATH**/ ?>
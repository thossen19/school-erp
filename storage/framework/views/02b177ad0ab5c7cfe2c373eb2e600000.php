<?php
    $colors = [
        'primary' => 'bg-primary bg-gradient',
        'success' => 'bg-success bg-gradient',
        'info' => 'bg-info bg-gradient',
        'warning' => 'bg-warning bg-gradient',
        'danger' => 'bg-danger bg-gradient',
    ];
    $bgClass = $colors[$color] ?? '';
?>
<div class="card stats-card shadow-sm border-0 <?php echo e($bgClass); ?>">
    <div class="card-body text-white">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="stats-label text-white-50"><?php echo e($title); ?></span>
            <div class="stats-icon bg-white bg-opacity-25">
                <i class="fas <?php echo e($icon); ?>"></i>
            </div>
        </div>
        <div class="stats-value mb-1"><?php echo e($value); ?></div>
        <?php if($trend): ?>
            <small class="text-white-50">
                <i class="fas fa-arrow-<?php echo e($trendUp ? 'up' : 'down'); ?> me-1"></i><?php echo e($trend); ?>

            </small>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Lupu\Desktop\laravel\aischool\resources\views/components/stats-card.blade.php ENDPATH**/ ?>
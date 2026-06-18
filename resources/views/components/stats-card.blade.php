@php
    $colors = [
        'primary' => 'bg-primary bg-gradient',
        'success' => 'bg-success bg-gradient',
        'info' => 'bg-info bg-gradient',
        'warning' => 'bg-warning bg-gradient',
        'danger' => 'bg-danger bg-gradient',
    ];
    $bgClass = $colors[$color] ?? '';
@endphp
<div class="card stats-card shadow-sm border-0 {{ $bgClass }}">
    <div class="card-body text-white">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="stats-label text-white-50">{{ $title }}</span>
            <div class="stats-icon bg-white bg-opacity-25">
                <i class="fas {{ $icon }}"></i>
            </div>
        </div>
        <div class="stats-value mb-1">{{ $value }}</div>
        @if($trend)
            <small class="text-white-50">
                <i class="fas fa-arrow-{{ $trendUp ? 'up' : 'down' }} me-1"></i>{{ $trend }}
            </small>
        @endif
    </div>
</div>

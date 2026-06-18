@props(['type' => 'info', 'dismissible' => true, 'icon' => null])
@php
    $icons = ['success' => 'fa-check-circle', 'danger' => 'fa-exclamation-circle', 'warning' => 'fa-exclamation-triangle', 'info' => 'fa-info-circle'];
    $iconClass = $icon ?? ($icons[$type] ?? 'fa-info-circle');
@endphp
<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if($iconClass)<i class="fas {{ $iconClass }} me-2"></i>@endif
    {{ $slot }}
    @if($dismissible)<button type="button" class="btn-close" data-bs-dismiss="alert"></button>@endif
</div>
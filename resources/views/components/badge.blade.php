@props(['type' => 'primary', 'pill' => false])
<span class="badge bg-{{ $type }} {{ $pill ? 'rounded-pill' : '' }}" {{ $attributes }}>
    {{ $slot }}
</span>
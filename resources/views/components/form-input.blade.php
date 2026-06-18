@props(['name', 'label' => '', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => '', 'help' => ''])
<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
        value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }} {{ $attributes }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
@props(['name', 'label' => '', 'value' => '', 'required' => false, 'rows' => 3, 'placeholder' => ''])
<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    @endif
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
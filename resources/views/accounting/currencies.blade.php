@extends('layouts.app')
@section('title', 'Currencies')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-coins me-2"></i>Currencies</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Currencies</li></ol></nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2"><i class="fas fa-exclamation-triangle me-1"></i>{{ session('error') }}<button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2">
        <i class="fas fa-exclamation-circle me-1"></i>
        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <x-table :headers="['Abbreviation','Symbol','Currency Name','Hundredths Name','Country','Auto Update','Actions']">
            @forelse($currencies as $c)
                <tr class="{{ $defaultCurrency && $c->id === $defaultCurrency->id ? 'table-primary' : '' }}">
                    <td class="fw-bold font-monospace">{{ $c->code }}</td>
                    <td class="fw-bold">{{ $c->symbol }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->hundreds_name }}</td>
                    <td>{{ $c->country ?? '-' }}</td>
                    <td class="text-center">
                        @if($defaultCurrency && $c->id === $defaultCurrency->id)
                            <span class="badge bg-primary"><i class="fas fa-home me-1"></i>Home</span>
                        @else
                            <i class="fas {{ $c->auto_update ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' }}"></i>
                            <span class="small">{{ $c->auto_update ? 'Yes' : 'No' }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('accounting.currencies', ['edit' => $c->id]) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                            @if(!$defaultCurrency || $c->id !== $defaultCurrency->id)
                                <a href="{{ route('accounting.currencies', ['set_default' => $c->id]) }}" class="btn btn-sm btn-outline-warning" title="Set as Home Currency" onclick="return confirm('Set {{ $c->code }} as the default/home currency?')"><i class="fas fa-home"></i></a>
                                <form method="POST" action="{{ route('accounting.currencies') }}" class="d-inline" onsubmit="return confirm('Delete {{ $c->code }}?')">
                                    @csrf
                                    <input type="hidden" name="delete" value="{{ $c->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No currencies found.</td></tr>
            @endforelse
        </x-table>
    </div>
    @if($defaultCurrency)
        <div class="card-footer py-2"><small class="text-primary"><i class="fas fa-info-circle me-1"></i><strong>{{ $defaultCurrency->code }}</strong> is the home currency. Click <i class="fas fa-home text-warning"></i> to change it.</small></div>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        @php $isEditMode = $editCurrency || old('edit_id'); @endphp
        <h5 class="mb-0"><i class="fas fa-{{ $isEditMode ? 'edit' : 'plus' }} me-1"></i>{{ $isEditMode ? 'Edit Currency' : 'Add New Currency' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('accounting.currencies') }}" class="row g-3">
            @csrf
            @php $editId = old('edit_id', $editCurrency->id ?? null); @endphp
            @if($editId)
                <input type="hidden" name="edit_id" value="{{ $editId }}">
            @endif

            @php
                $codeVal = old('code', $editCurrency->code ?? '');
            @endphp
            <div class="col-md-3">
                <label class="form-label fw-semibold">Currency Abbreviation <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" maxlength="10" value="{{ $codeVal }}" {{ $editId ? 'readonly' : '' }} required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Currency Symbol <span class="text-danger">*</span></label>
                <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" maxlength="10" value="{{ old('symbol', $editCurrency->symbol ?? '') }}" required>
                @error('symbol')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Currency Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" maxlength="100" value="{{ old('name', $editCurrency->name ?? '') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Hundredths Name <span class="text-danger">*</span></label>
                <input type="text" name="hundreds_name" class="form-control @error('hundreds_name') is-invalid @enderror" maxlength="50" value="{{ old('hundreds_name', $editCurrency->hundreds_name ?? '') }}" required>
                @error('hundreds_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Country</label>
                <input type="text" name="country" class="form-control" maxlength="100" value="{{ old('country', $editCurrency->country ?? '') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" name="auto_update" class="form-check-input" id="autoUpdate" value="1" {{ old('auto_update', $editCurrency->auto_update ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="autoUpdate">Automatic exchange rate update</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>{{ $isEditMode ? 'Update' : 'Add New' }}</button>
                @if($isEditMode)
                    <a href="{{ route('accounting.currencies') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

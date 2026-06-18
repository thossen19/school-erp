@extends('layouts.app')
@section('title', 'Collect Fee')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Collect Fee</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.fee-collection') }}">Fee Collection</a></li><li class="breadcrumb-item active">Collect Fee</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('fees.store') }}" id="feeForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Student <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-select form-select-sm @error('student_id') is-invalid @enderror" required>
                        <option value="">Select Student</option>
                        @foreach($students as $s)
                        <option value="{{ $s->id }}" data-class="{{ $s->class_id }}" {{ old('student_id')==$s->id?'selected':'' }}>{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fee Category <span class="text-danger">*</span></label>
                    <select name="fee_category_id" class="form-select form-select-sm @error('fee_category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('fee_category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('fee_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fee Structure <span class="text-danger">*</span></label>
                    <select name="fee_structure_id" class="form-select form-select-sm @error('fee_structure_id') is-invalid @enderror" required onchange="updateFeeDetails(this)">
                        <option value="">Select Structure</option>
                        @foreach($structures as $st)
                        <option value="{{ $st->id }}" data-amount="{{ $st->amount }}" data-installment="{{ $st->is_installment }}" {{ old('fee_structure_id')==$st->id?'selected':'' }}>{{ $st->name }} - {{ number_format($st->amount,2) }} ({{ $st->class_name ?? 'All' }})</option>
                        @endforeach
                    </select>
                    @error('fee_structure_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control form-control-sm @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control form-control-sm @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount') }}" required>
                    @error('paid_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Discount Amount</label>
                    <input type="number" step="0.01" name="discount_amount" class="form-control form-control-sm" value="{{ old('discount_amount', 0) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fine Amount</label>
                    <input type="number" step="0.01" name="fine_amount" class="form-control form-control-sm" value="{{ old('fine_amount', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select form-select-sm @error('payment_method') is-invalid @enderror" required onchange="togglePaymentFields(this)">
                        <option value="">Select Method</option>
                        <option value="cash" {{ old('payment_method')=='cash'?'selected':'' }}>Cash</option>
                        <option value="cheque" {{ old('payment_method')=='cheque'?'selected':'' }}>Cheque</option>
                        <option value="online_transfer" {{ old('payment_method')=='online_transfer'?'selected':'' }}>Online Transfer</option>
                        <option value="bank_deposit" {{ old('payment_method')=='bank_deposit'?'selected':'' }}>Bank Deposit</option>
                        <option value="card" {{ old('payment_method')=='card'?'selected':'' }}>Card</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4" id="transactionField" style="display:none">
                    <label class="form-label fw-semibold">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-control form-control-sm" value="{{ old('transaction_id') }}">
                </div>
                <div class="col-md-4" id="chequeField" style="display:none">
                    <label class="form-label fw-semibold">Cheque No</label>
                    <input type="text" name="cheque_no" class="form-control form-control-sm" value="{{ old('cheque_no') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Payment Mode</label>
                    <select name="payment_mode" class="form-select form-select-sm">
                        <option value="">Select Mode</option>
                        <option value="cash" {{ old('payment_mode')=='cash'?'selected':'' }}>Cash</option>
                        <option value="cheque" {{ old('payment_mode')=='cheque'?'selected':'' }}>Cheque</option>
                        <option value="online" {{ old('payment_mode')=='online'?'selected':'' }}>Online</option>
                        <option value="bank_deposit" {{ old('payment_mode')=='bank_deposit'?'selected':'' }}>Bank Deposit</option>
                        <option value="card" {{ old('payment_mode')=='card'?'selected':'' }}>Card</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control form-control-sm @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Receipt Number</label>
                    <input type="text" name="receipt_number" class="form-control form-control-sm" value="{{ old('receipt_number', 'RCT-'.date('Ymd').'-'.rand(100,999)) }}" placeholder="Auto">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Remarks</label>
                    <textarea name="remarks" class="form-control form-control-sm" rows="2">{{ old('remarks') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Collect Fee</button>
                <a href="{{ route('fees.fee-collection') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function updateFeeDetails(sel) {
    let opt = sel.options[sel.selectedIndex];
    let amount = opt.dataset.amount || 0;
    document.getElementById('amount').value = amount;
    document.getElementById('paid_amount').value = amount;
}
function togglePaymentFields(sel) {
    let v = sel.value;
    document.getElementById('transactionField').style.display = (v === 'online_transfer' || v === 'card') ? '' : 'none';
    document.getElementById('chequeField').style.display = v === 'cheque' ? '' : 'none';
}
togglePaymentFields(document.querySelector('[name="payment_method"]'));
</script>
@endpush

<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Receipt - {{ $receipt->receipt_number ?? $receipt->receipt_no ?? 'N/A' }}</title>
<style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif}
    body{padding:40px;color:#333}
    .receipt{max-width:750px;margin:0 auto;border:2px solid #1e2a3a;padding:30px}
    .header{text-align:center;border-bottom:2px dashed #1e2a3a;padding-bottom:15px;margin-bottom:20px}
    .header h2{color:#1e2a3a;margin-bottom:5px;font-size:1.5rem}
    .header .school-details{font-size:0.85rem;color:#555;line-height:1.6}
    .header .receipt-title{font-size:1.1rem;font-weight:700;margin-top:8px;color:#0d6efd}
    .info-row{display:flex;justify-content:space-between;margin-bottom:6px;font-size:0.9rem}
    .info-row .label{font-weight:600;color:#555;min-width:120px}
    .info-row .value{flex:1}
    table{width:100%;border-collapse:collapse;margin:15px 0}
    th,td{border:1px solid #ddd;padding:8px 10px;text-align:left;font-size:0.88rem}
    th{background:#1e2a3a;color:#fff;font-weight:600}
    .totals{text-align:right;margin-top:10px;font-size:0.9rem}
    .totals .total-line{margin-bottom:4px}
    .totals .total-line .lbl{display:inline-block;width:120px;font-weight:600;color:#555}
    .totals .total-line .val{display:inline-block;width:100px;text-align:right}
    .grand-total{font-size:1.05rem;font-weight:700;color:#0d6efd;border-top:2px solid #1e2a3a;padding-top:6px;margin-top:6px}
    .footer{text-align:center;margin-top:20px;padding-top:15px;border-top:1px solid #ddd;font-size:0.8rem;color:#888}
    .signature{margin-top:25px;display:flex;justify-content:space-between;font-size:0.85rem}
    .signature div{text-align:center;min-width:150px}
    .signature .line{border-top:1px solid #333;margin-top:35px;padding-top:5px}
    @media print{body{padding:0}.receipt{border:none;max-width:100%}@page{margin:15mm}}
</style>
</head>
<body onload="window.print()">
<div class="receipt">
    <div class="header">
        <h2>{{ $school->name ?? 'School Name' }}</h2>
        <div class="school-details">
            {{ $school->address ?? '' }}, {{ $school->city ?? '' }} {{ $school->pincode ?? '' }}<br>
            @if($school->phone)Phone: {{ $school->phone }} | @endif
            @if($school->email)Email: {{ $school->email }} | @endif
            @if($school->e_tin)E-TIN: {{ $school->e_tin }} @endif
            @if($school->registration_no) | Reg: {{ $school->registration_no }} @endif
        </div>
        <div class="receipt-title">PAYMENT RECEIPT</div>
    </div>

    <div class="info-row"><span class="label">Receipt No:</span><span class="value">{{ $receipt->receipt_number ?? $receipt->receipt_no ?? 'N/A' }}</span></div>
    <div class="info-row"><span class="label">Date:</span><span class="value">{{ $receipt->payment_date }}</span></div>
    <div class="info-row"><span class="label">Student Name:</span><span class="value">{{ $receipt->first_name }} {{ $receipt->last_name }}</span></div>
    <div class="info-row"><span class="label">Admission No:</span><span class="value">{{ $receipt->admission_no ?? 'N/A' }}</span></div>
    <div class="info-row"><span class="label">Roll Number:</span><span class="value">{{ $receipt->roll_number ?? 'N/A' }}</span></div>
    <div class="info-row"><span class="label">Class/Section:</span><span class="value">{{ $receipt->class_name ?? 'N/A' }} {{ $receipt->section_name ? '/ '.$receipt->section_name : '' }}</span></div>
    <div class="info-row"><span class="label">Fee Category:</span><span class="value">{{ $receipt->category_name ?? 'N/A' }}</span></div>
    <div class="info-row"><span class="label">Fee Structure:</span><span class="value">{{ $receipt->structure_name ?? 'N/A' }}</span></div>
    <div class="info-row"><span class="label">Payment Method:</span><span class="value">{{ ucfirst($receipt->payment_mode ?? $receipt->payment_method ?? 'N/A') }}</span></div>
    @if($receipt->transaction_id)
    <div class="info-row"><span class="label">Transaction ID:</span><span class="value">{{ $receipt->transaction_id }}</span></div>
    @endif
    @if($receipt->cheque_no)
    <div class="info-row"><span class="label">Cheque No:</span><span class="value">{{ $receipt->cheque_no }}</span></div>
    @endif

    <table>
        <thead><tr><th>Description</th><th style="width:120px;text-align:right">Amount</th></tr></thead>
        <tbody>
            <tr><td>Fee Amount</td><td style="text-align:right">{{ number_format($receipt->amount, 2) }}</td></tr>
            @if($receipt->discount_amount > 0)<tr><td>Discount</td><td style="text-align:right;color:#28a745">-{{ number_format($receipt->discount_amount, 2) }}</td></tr>@endif
            @if($receipt->fine_amount > 0)<tr><td>Fine / Late Fee</td><td style="text-align:right;color:#dc3545">{{ number_format($receipt->fine_amount, 2) }}</td></tr>@endif
            <tr style="font-weight:700;background:#f8f9fa"><td>Total Amount</td><td style="text-align:right">{{ number_format($receipt->total_amount, 2) }}</td></tr>
            <tr><td>Paid Amount</td><td style="text-align:right;color:#28a745;font-weight:700">{{ number_format($receipt->paid_amount, 2) }}</td></tr>
            @if($receipt->balance_amount > 0)<tr><td>Balance Due</td><td style="text-align:right;color:#dc3545;font-weight:700">{{ number_format($receipt->balance_amount, 2) }}</td></tr>@endif
        </tbody>
    </table>

    @if($receipt->remarks)
    <div class="info-row"><span class="label">Remarks:</span><span class="value">{{ $receipt->remarks }}</span></div>
    @endif

    <div class="signature">
        <div><span class="line">Authorised Signature</span></div>
        <div><span class="line">Receiver's Signature</span></div>
    </div>

    <div class="footer">
        This is a computer-generated receipt. | {{ $school->name ?? '' }}
    </div>
</div>
</body>
</html>

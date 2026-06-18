<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Application Form - {{ $application->form_number }}</title>
<style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif}
    body{padding:30px;color:#333}
    .app-form{max-width:800px;margin:0 auto;border:2px solid #1e2a3a;padding:25px}
    .header{text-align:center;border-bottom:2px solid #1e2a3a;padding-bottom:12px;margin-bottom:18px}
    .header h2{color:#1e2a3a;margin-bottom:3px}
    .header .school-info{font-size:0.82rem;color:#555;line-height:1.5}
    .header .form-title{font-size:1.1rem;font-weight:700;margin-top:6px;color:#0d6efd;text-transform:uppercase;letter-spacing:1px}
    .photo-area{float:right;width:100px;height:120px;border:2px solid #333;text-align:center;font-size:0.7rem;color:#888;display:flex;align-items:center;justify-content:center;margin-bottom:10px;overflow:hidden}
    .photo-area img{width:100%;height:100%;object-fit:cover}
    .details{clear:right}
    table{width:100%;border-collapse:collapse;margin-bottom:10px}
    td{padding:5px 8px;border:1px solid #ccc;font-size:0.85rem;vertical-align:top}
    td.label{font-weight:600;background:#f5f5f5;width:35%;color:#333}
    td.value{width:65%}
    .section-title{background:#1e2a3a;color:#fff;padding:5px 8px;font-size:0.85rem;font-weight:600;margin-top:10px;margin-bottom:0}
    .footer{text-align:center;margin-top:15px;padding-top:10px;border-top:1px solid #ccc;font-size:0.75rem;color:#888}
    .signature{margin-top:20px;display:flex;justify-content:space-between;font-size:0.8rem}
    .signature div{text-align:center;min-width:150px}
    .signature .line{border-top:1px solid #333;margin-top:40px;padding-top:5px}
    @media print{body{padding:0}.app-form{border:none;max-width:100%}@page{margin:12mm}}
</style>
</head>
<body onload="window.print()">
<div class="app-form">
    <div class="header">
        <h2>{{ $school->name ?? 'School Name' }}</h2>
        <div class="school-info">
            {{ $school->address ?? '' }}{{ $school->city ? ', '.$school->city : '' }}{{ $school->state ? ', '.$school->state : '' }}{{ $school->pincode ? ' - '.$school->pincode : '' }}<br>
            @if($school->phone)Phone: {{ $school->phone }} | @endif
            @if($school->email)Email: {{ $school->email }} @endif
            @if($school->registration_no) | Reg: {{ $school->registration_no }} @endif
        </div>
        <div class="form-title">Admission Application Form</div>
    </div>

    <div class="photo-area">
        @if($application->photo)
            <img src="{{ asset('storage/'.$application->photo) }}" alt="Photo">
        @else
            Photo
        @endif
    </div>

    <div class="details">
        <div class="section-title">Application Information</div>
        <table>
            <tr><td class="label">Form Number</td><td class="value">{{ $application->form_number }}</td></tr>
            <tr><td class="label">Applied Date</td><td class="value">{{ \Carbon\Carbon::parse($application->applied_date)->format('M d, Y') }}</td></tr>
            <tr><td class="label">Academic Year</td><td class="value">{{ $application->academic_year ?? '-' }}</td></tr>
            <tr><td class="label">Status</td><td class="value">{{ ucfirst($application->status) }}</td></tr>
        </table>

        <div class="section-title">Personal Information</div>
        <table>
            <tr><td class="label">Applicant Name</td><td class="value">{{ $application->applicant_name }}</td></tr>
            <tr><td class="label">Date of Birth</td><td class="value">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</td></tr>
            <tr><td class="label">Birth Cert. No.</td><td class="value">{{ $application->birth_cert_no ?? '-' }}</td></tr>
            <tr><td class="label">Gender</td><td class="value">{{ ucfirst($application->gender) }}</td></tr>
            <tr><td class="label">Phone</td><td class="value">{{ $application->phone }}</td></tr>
            <tr><td class="label">Email</td><td class="value">{{ $application->email ?? '-' }}</td></tr>
            <tr><td class="label">Address</td><td class="value">{{ $application->address ?? '-' }}</td></tr>
        </table>

        <div class="section-title">Academic Information</div>
        <table>
            <tr><td class="label">Applying for Class</td><td class="value">{{ $application->class_name ?? '-' }}</td></tr>
            <tr><td class="label">Previous School</td><td class="value">{{ $application->previous_school ?? '-' }}</td></tr>
        </table>

        <div class="section-title">Parent / Guardian Information</div>
        <table>
            <tr><td class="label">Father's Name</td><td class="value">{{ $application->father_name ?? '-' }}</td></tr>
            <tr><td class="label">Father's Phone</td><td class="value">{{ $application->father_phone ?? '-' }}</td></tr>
            <tr><td class="label">Mother's Name</td><td class="value">{{ $application->mother_name ?? '-' }}</td></tr>
            <tr><td class="label">Mother's Phone</td><td class="value">{{ $application->mother_phone ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="signature">
        <div><div class="line">Applicant / Parent Signature</div></div>
        <div><div class="line">Authorized Signature</div></div>
    </div>

    <div class="footer">
        This is a computer-generated application form. | {{ $school->name ?? 'School' }}
    </div>
</div>
</body>
</html>

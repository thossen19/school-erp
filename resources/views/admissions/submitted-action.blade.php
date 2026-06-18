<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Submitted - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body{font-family:'Segoe UI',Arial,sans-serif;background:#f4f4f4;display:flex;align-items:center;min-height:100vh}
        .card{max-width:600px;margin:auto;border:none;border-radius:0;box-shadow:0 8px 30px rgba(0,0,0,0.08)}
        .card-body{padding:3rem 2.5rem;text-align:center}
        .check-icon{width:80px;height:80px;border-radius:50%;background:#d4edda;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem}
        .check-icon i{font-size:2.5rem;color:#28a745}
        h3{font-weight:700;color:#1e2a3a;margin-bottom:0.5rem}
        .form-no{font-size:1.1rem;color:#0d6efd;font-weight:600;margin-bottom:1.5rem}
        .desc{color:#666;margin-bottom:2rem;font-size:0.95rem}
        .actions{display:flex;flex-direction:column;gap:0.75rem}
        .actions .btn{border-radius:0;padding:0.75rem 1.5rem;font-weight:600;font-size:0.95rem}
        .back-link{margin-top:1.5rem;display:block;color:#888;text-decoration:none;font-size:0.88rem}
        .back-link:hover{color:#0d6efd}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="check-icon"><i class="fas fa-check"></i></div>
                <h3>Application Submitted!</h3>
                <div class="form-no"><i class="fas fa-file-alt me-2"></i>{{ $application->form_number }}</div>
                <p class="desc">Your application has been received successfully. You can view or print the application form below.</p>
                <div class="actions">
                    <a href="{{ route('admissions.application-forms.print', $application->id) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-print me-2"></i>Print Application</a>
                    <button class="btn btn-outline-info" onclick="viewDetails()"><i class="fas fa-eye me-2"></i>View Application</button>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Application Page</a>
                </div>
                <a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left me-1"></i>Back to Application Page</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:0">
                <div class="modal-header">
                    <h5 class="modal-title">Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th style="width:35%">Form No</th><td>{{ $application->form_number }}</td></tr>
                        <tr><th>Name</th><td>{{ $application->applicant_name }}</td></tr>
                        <tr><th>DOB</th><td>{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</td></tr>
                        <tr><th>Birth Cert No.</th><td>{{ $application->birth_cert_no ?? '-' }}</td></tr>
                        <tr><th>Gender</th><td>{{ ucfirst($application->gender) }}</td></tr>
                        <tr><th>Phone</th><td>{{ $application->phone }}</td></tr>
                        <tr><th>Email</th><td>{{ $application->email ?? '-' }}</td></tr>
                        <tr><th>Class</th><td>{{ $application->class_name ?? '-' }}</td></tr>
                        <tr><th>Address</th><td>{{ $application->address ?? '-' }}</td></tr>
                        <tr><th>Father</th><td>{{ $application->father_name ?? '-' }} ({{ $application->father_phone ?? '-' }})</td></tr>
                        <tr><th>Mother</th><td>{{ $application->mother_name ?? '-' }} ({{ $application->mother_phone ?? '-' }})</td></tr>
                        <tr><th>Previous School</th><td>{{ $application->previous_school ?? '-' }}</td></tr>
                        <tr><th>Status</th><td><span class="badge bg-warning">{{ ucfirst($application->status) }}</span></td></tr>
                        <tr><th>Applied</th><td>{{ \Carbon\Carbon::parse($application->applied_date)->format('M d, Y') }}</td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function viewDetails() {
        var modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        modal.show();
    }
    </script>
</body>
</html>

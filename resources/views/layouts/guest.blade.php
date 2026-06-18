<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School ERP'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .auth-card .card-header { background: transparent; border-bottom: none; text-align: center; padding: 30px 30px 0; }
        .auth-card .card-body { padding: 20px 30px 30px; }
        .auth-card .card-footer { background: transparent; border-top: none; text-align: center; padding: 0 30px 30px; }
        .auth-logo { width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
        .auth-logo i { font-size: 1.8rem; color: #fff; }
        .auth-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 5px; }
        .auth-subtitle { color: #6c757d; font-size: 0.9rem; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.15); }
        .btn-auth { background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: #fff; padding: 10px 20px; font-weight: 600; width: 100%; border-radius: 8px; }
        .btn-auth:hover { background: linear-gradient(135deg, #5a6fd6, #6a4192); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

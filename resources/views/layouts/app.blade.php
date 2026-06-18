@php
    $unreadNotifications = Auth::check() ? Auth::user()->notifications()->whereNull('read_at')->orderBy('created_at', 'desc')->limit(10)->get() : collect();
    $unreadCount = $unreadNotifications->count();
    $recentNotifications = Auth::check() ? Auth::user()->notifications()->orderBy('created_at', 'desc')->limit(5)->get() : collect();
    $schoolShortCode = Auth::check() ? \Illuminate\Support\Facades\DB::table('schools')->where('id', Auth::user()->school_id ?? session('school_id', 1))->value('short_code') : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School ERP')) - {{ config('app.name', 'School ERP') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 60px;
            --navbar-height: 60px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        [data-bs-theme="dark"] .bg-sidebar { background: linear-gradient(180deg, #1a1d23 0%, #22252b 100%); }
        [data-bs-theme="light"] .bg-sidebar { background: linear-gradient(180deg, #1e2a3a 0%, #253344 100%); }
        .bg-sidebar { height: 100vh; color: #fff; transition: width 0.3s ease; width: var(--sidebar-width); position: fixed; top: 0; left: 0; z-index: 1040; display: flex; flex-direction: column; overflow: hidden; }
        .bg-sidebar::-webkit-scrollbar { width: 4px; height: 4px; }
        .bg-sidebar::-webkit-scrollbar-track { background: transparent; }
        .bg-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
        .bg-sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }
        .sidebar-scroll { flex: 1; overflow-y: auto; overflow-x: hidden; min-height: 0; }
        .sidebar-header { padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; }
        .sidebar-header .logo-text { font-size: 1.2rem; font-weight: 700; white-space: nowrap; overflow: hidden; background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .sidebar-nav { padding: 8px 0; }
        .sidebar-nav .nav-category { padding: 14px 20px 5px; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.2px; color: rgba(255,255,255,0.35); font-weight: 700; border-top: 1px solid rgba(255,255,255,0.06); margin-top: 4px; }
        .sidebar-nav .nav-category:first-child { border-top: none; margin-top: 0; }
        .sidebar-nav .nav-category::before { content: ''; display: inline-block; width: 12px; height: 2px; background: rgba(255,255,255,0.2); margin-right: 8px; vertical-align: middle; border-radius: 2px; }
        .sidebar-nav .nav-item { position: relative; }
        .sidebar-nav .nav-link { color: rgba(255,255,255,0.65); padding: 9px 20px; display: flex; align-items: center; gap: 12px; text-decoration: none; transition: all 0.2s ease; border-left: 3px solid transparent; font-size: 0.92rem; }
        .sidebar-nav .nav-link:hover { color: #fff; background: rgba(255,255,255,0.06); border-left-color: rgba(255,255,255,0.2); }
        .sidebar-nav .nav-link.active { color: #fff; background: rgba(13,110,253,0.12); border-left-color: #0d6efd; box-shadow: inset 0 0 20px rgba(13,110,253,0.05); }
        .sidebar-nav .nav-link i { width: 20px; text-align: center; font-size: 1rem; flex-shrink: 0; }
        .sidebar-nav .nav-link .link-text { white-space: nowrap; }
        .sidebar-nav .nav-link .badge { margin-left: auto; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; }
        .sidebar-nav .collapse-submenu { background: rgba(0,0,0,0.12); border-left: 2px solid rgba(13,110,253,0.08); margin: 0 0 0 20px; border-radius: 0 4px 4px 0; }
        .sidebar-nav .collapse-submenu .nav-link { padding-left: 16px; font-size: 0.85rem; border-left: none; position: relative; }
        .sidebar-nav .collapse-submenu .nav-link::before { content: ''; display: inline-block; width: 5px; height: 5px; border-radius: 50%; background: rgba(255,255,255,0.2); flex-shrink: 0; transition: all 0.2s; }
        .sidebar-nav .collapse-submenu .nav-link:hover::before, .sidebar-nav .collapse-submenu .nav-link.active::before { background: #0d6efd; box-shadow: 0 0 6px rgba(13,110,253,0.5); }
        .sidebar-nav .collapse-submenu .nav-link.active { background: rgba(13,110,253,0.08); color: #fff; }
        .sidebar-nav .collapse-submenu .nav-link:hover { background: rgba(255,255,255,0.04); }
        .sidebar-nav .nav-link.highlight { color: #60a5fa; background: rgba(96,165,250,0.08); border-left-color: #60a5fa; }
        .sidebar-filter { padding: 8px 16px 4px; }
        .sidebar-filter input { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.06); color: #fff; font-size: 0.8rem; padding: 6px 12px 6px 32px; border-radius: 6px; width: 100%; transition: all 0.2s; }
        .sidebar-filter input::placeholder { color: rgba(255,255,255,0.3); }
        .sidebar-filter input:focus { background: rgba(255,255,255,0.12); border-color: rgba(13,110,253,0.4); outline: none; }
        .sidebar-filter .filter-icon { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.3); font-size: 0.75rem; pointer-events: none; }
        .collapse:not(.show) { display: none; }
        .collapsing { height: 0; overflow: hidden; transition: height 0.25s ease; }
        .main-content { margin-left: var(--sidebar-width); transition: margin-left 0.3s ease; min-height: 100vh; }
        .top-navbar { height: var(--navbar-height); background: var(--bs-body-bg); border-bottom: 1px solid var(--bs-border-color); display: flex; align-items: center; padding: 0 20px; position: sticky; top: 0; z-index: 1030; }
        .top-navbar .navbar-brand-mobile { display: none; }
        .page-content { padding: 20px; }
        .sidebar-toggle { background: none; border: none; color: var(--bs-body-color); font-size: 1.3rem; cursor: pointer; padding: 5px; margin-right: 15px; }
        .avatar-circle { width: 36px; height: 36px; border-radius: 50%; background: #0d6efd; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; }
        .notification-dropdown { max-height: 350px; overflow-y: auto; width: 320px; }
        .notification-dropdown .dropdown-item { white-space: normal; padding: 10px 15px; border-bottom: 1px solid var(--bs-border-color); }
        .notification-dropdown .dropdown-item:last-child { border-bottom: none; }
        .stats-card { border-radius: 12px; border: none; transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-3px); }
        .stats-card .stats-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .stats-card .stats-value { font-size: 1.8rem; font-weight: 700; }
        .stats-card .stats-label { font-size: 0.85rem; color: var(--bs-secondary-color); }
        .breadcrumb { background: transparent; padding: 0; margin-bottom: 0; }
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
        .search-bar { max-width: 350px; }
        .table-actions { display: flex; gap: 5px; }
        .filter-bar { background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .timeline { position: relative; padding-left: 30px; }
        .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: var(--bs-border-color); }
        .timeline-item { position: relative; padding-bottom: 20px; }
        .timeline-item::before { content: ''; position: absolute; left: -24px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #0d6efd; border: 2px solid var(--bs-body-bg); }
        .timeline-item .timeline-date { font-size: 0.8rem; color: var(--bs-secondary-color); }
        @media (max-width: 768px) {
            .bg-sidebar { width: 0; overflow: hidden; }
            .bg-sidebar.show { width: var(--sidebar-width); }
            .main-content { margin-left: 0; }
            .top-navbar .navbar-brand-mobile { display: block; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1035; }
            .sidebar-overlay.show { display: block; }
            .page-content { padding: 15px; }
            .search-bar { max-width: 100%; }
        }
        .nav-link.has-submenu::after { content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; margin-left: auto; font-size: 0.7rem; transition: transform 0.2s; }
        .nav-link.has-submenu[aria-expanded="true"]::after { transform: rotate(180deg); }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
        .wizard-step { display: flex; align-items: center; gap: 10px; padding: 15px; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .wizard-step.active { background: var(--bs-primary-bg-subtle); border: 1px solid var(--bs-primary); }
        .wizard-step .step-number { width: 32px; height: 32px; border-radius: 50%; background: var(--bs-secondary-bg); display: flex; align-items: center; justify-content: center; font-weight: 600; }
        .wizard-step.active .step-number { background: var(--bs-primary); color: #fff; }
        .wizard-step.completed .step-number { background: var(--bs-success); color: #fff; }
        .profile-header { background: linear-gradient(135deg, #0d6efd, #0dcaf0); border-radius: 12px; padding: 30px; color: #fff; margin-bottom: 20px; }
        .profile-avatar { width: 100px; height: 100px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); object-fit: cover; }
        .chart-container { position: relative; height: 300px; width: 100%; }
        @media print {
            .bg-sidebar, .top-navbar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="bg-sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="logo-text"><i class="fas fa-graduation-cap me-2"></i>{{ $schoolShortCode ?? 'School' }}</span>
            <button class="btn btn-sm btn-outline-light d-none d-md-block" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <button class="btn btn-sm btn-outline-light d-md-none" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sidebar-scroll">
            @include('layouts.navigation')
        </div>
    </aside>

    <div class="main-content" id="mainContent">
        <nav class="top-navbar">
            <button class="sidebar-toggle d-md-none" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="navbar-brand-mobile fw-bold">
                <i class="fas fa-graduation-cap me-2"></i>{{ $schoolShortCode ?? 'School' }}
            </div>
            <div class="ms-auto d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary position-relative" data-bs-toggle="dropdown" id="notificationBell">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadCount }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width:320px">
                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route_if_exists('notifications.markAllRead') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">Mark all read</button>
                            </form>
                        @endif
                    </h6>
                    <div style="max-height:350px;overflow-y:auto">
                        @forelse($recentNotifications as $notif)
                            @php $data = $notif->data; @endphp
                            <a class="dropdown-item {{ $notif->read_at ? '' : 'bg-light' }}" href="{{ !empty($data['url']) ? $data['url'] : '#' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas {{ $data['icon'] ?? 'fa-bell' }} text-{{ $data['color'] ?? 'primary' }}"></i>
                                    <div>
                                        <small class="fw-semibold d-block">{{ $data['title'] ?? class_basename($notif->type) }}</small>
                                        <small class="text-muted">{{ $data['message'] ?? '' }}</small>
                                        <small class="text-muted d-block">{{ $notif->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                                <small>No notifications yet</small>
                            </div>
                        @endforelse
                    </div>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item text-center" href="{{ route_if_exists('notifications.index') }}">
                        <small>View All Notifications</small>
                    </a>
                </div>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> EN
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2 text-primary"></i>English</a></li>
                        <li><a class="dropdown-item" href="#">French</a></li>
                        <li><a class="dropdown-item" href="#">Arabic</a></li>
                        <li><a class="dropdown-item" href="#">Spanish</a></li>
                    </ul>
                </div>

                <button class="btn btn-sm btn-outline-secondary" onclick="toggleTheme()" title="Toggle Theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                <div class="dropdown">
                    <a href="#" class="text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            <span class="d-none d-md-inline text-body">{{ Auth::user()->name }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route_if_exists('profile.show') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function toggleTheme() {
            let html = document.documentElement;
            let theme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', theme);
            document.getElementById('themeIcon').className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            fetch('/settings/theme', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }, body: JSON.stringify({ theme: theme }) });
        }
        document.addEventListener('DOMContentLoaded', function() {
            let theme = document.documentElement.getAttribute('data-bs-theme');
            document.getElementById('themeIcon').className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        });
        function filterSidebar(query) {
            query = query.toLowerCase().trim();
            document.querySelectorAll('.sidebar-nav .nav-item').forEach(item => {
                let text = item.textContent.toLowerCase();
                let matches = !query || text.includes(query);
                item.style.display = matches ? '' : 'none';
                if (matches && query) {
                    let parentCollapse = item.closest('.collapse');
                    if (parentCollapse && !parentCollapse.classList.contains('show')) {
                        let trigger = document.querySelector(`[href="#${parentCollapse.id}"]`);
                        if (trigger) trigger.click();
                    }
                    item.closest('.nav-item')?.querySelector('.nav-link')?.classList.add('highlight');
                } else {
                    item.querySelector('.highlight')?.classList.remove('highlight');
                }
            });
            document.querySelectorAll('.sidebar-nav .nav-category').forEach(cat => {
                let nextItems = [];
                let el = cat.nextElementSibling;
                while (el && el.classList.contains('nav-item')) { nextItems.push(el); el = el.nextElementSibling; }
                let visible = nextItems.some(it => it.style.display !== 'none');
                cat.style.display = !query || visible ? '' : 'none';
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
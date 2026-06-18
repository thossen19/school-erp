:root {
    --burnt-orange: #BF5700;
    --burnt-orange-dark: #9A4500;
    --burnt-orange-light: #E87D1E;
    --charcoal: #333F48;
    --light-gray: #F7F7F7;
    --medium-gray: #E5E5E5;
}
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #1D1D1D;
    overflow-x: hidden;
}
h1, h2, h3, h4, .heading-font {
    font-family: 'Playfair Display', Georgia, serif;
}

/* Top Utility Bar */
.utility-bar {
    background: var(--charcoal);
    font-size: 0.8rem;
    padding: 0.4rem 0;
    letter-spacing: 0.3px;
}
.utility-bar a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    padding: 0 0.75rem;
    border-right: 1px solid rgba(255,255,255,0.15);
    transition: color 0.2s;
}
.utility-bar a:last-child { border-right: none; }
.utility-bar a:hover { color: #fff; }

/* Main Navigation */
.main-nav {
    background: #fff;
    border-bottom: 1px solid var(--medium-gray);
    padding: 0;
}
.main-nav .navbar-brand {
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 900;
    font-size: 1.6rem;
    color: var(--charcoal) !important;
    letter-spacing: -0.5px;
    padding: 0.75rem 0;
}
.main-nav .navbar-brand small {
    font-family: 'Inter', sans-serif;
    font-weight: 400;
    font-size: 0.7rem;
    display: block;
    margin-top: -2px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--burnt-orange);
}
.main-nav .nav-link {
    color: var(--charcoal) !important;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 1.5rem 1rem !important;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}
.main-nav .nav-link:hover,
.main-nav .nav-link.active {
    border-bottom-color: var(--burnt-orange);
    color: var(--burnt-orange) !important;
}
.main-nav .btn-orange {
    background: var(--burnt-orange);
    color: #fff;
    font-weight: 600;
    border-radius: 4px;
    padding: 0.4rem 1.25rem;
    font-size: 0.82rem;
    border: 2px solid var(--burnt-orange);
    transition: all 0.2s;
}
.main-nav .btn-orange:hover {
    background: var(--burnt-orange-dark);
    border-color: var(--burnt-orange-dark);
    color: #fff;
}
.main-nav .dropdown-menu {
    border: none;
    border-radius: 0;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    padding: 0.5rem 0;
    margin-top: 0;
}
.main-nav .dropdown-menu .dropdown-item {
    padding: 0.5rem 1.5rem;
    font-size: 0.88rem;
    color: var(--charcoal);
    transition: all 0.15s;
}
.main-nav .dropdown-menu .dropdown-item:hover {
    background: var(--light-gray);
    color: var(--burnt-orange);
}
.main-nav .dropdown-menu .dropdown-item.active {
    background: var(--light-gray);
    color: var(--burnt-orange);
    font-weight: 600;
}
.main-nav .dropdown:hover .dropdown-menu {
    display: block;
}

/* Sections */
section { padding: 5rem 0; }
.section-title {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
}
.section-subtitle {
    color: #666;
    font-size: 1.05rem;
    max-width: 600px;
}
.section-bg-light { background: var(--light-gray); }

/* Feature Cards */
.feature-card {
    background: #fff;
    border: none;
    border-radius: 0;
    padding: 2.5rem 2rem;
    height: 100%;
    transition: all 0.3s;
    border-bottom: 4px solid transparent;
    box-shadow: 0 2px 15px rgba(0,0,0,0.04);
}
.feature-card:hover {
    border-bottom-color: var(--burnt-orange);
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.08);
}
.feature-card .icon {
    font-size: 2.5rem;
    color: var(--burnt-orange);
    margin-bottom: 1.25rem;
}
.feature-card h5 {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 1.15rem;
    margin-bottom: 0.75rem;
}
.feature-card p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.7;
    margin-bottom: 0;
}

/* Footer */
.site-footer {
    background: #1a1a2e;
    color: rgba(255,255,255,0.7);
    padding: 4rem 0 2rem;
    font-size: 0.9rem;
}
.site-footer h6 {
    color: #fff;
    font-weight: 600;
    margin-bottom: 1.25rem;
    font-family: 'Inter', sans-serif;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.8rem;
}
.site-footer a {
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    display: block;
    padding: 0.3rem 0;
    transition: color 0.2s;
}
.site-footer a:hover { color: var(--burnt-orange); }
.site-footer .social a {
    display: inline-block;
    font-size: 1.2rem;
    margin-right: 1rem;
    color: rgba(255,255,255,0.5);
}
.site-footer .social a:hover { color: var(--burnt-orange); }
.site-footer .footer-brand {
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 700;
    font-size: 1.5rem;
    color: #fff;
}
.site-footer .footer-brand small {
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    display: block;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--burnt-orange);
}
.site-footer .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding-top: 1.5rem;
    margin-top: 2rem;
    font-size: 0.8rem;
}

/* System Breadcrumb - UT Austin style */
.system-breadcrumb {
    --bs-breadcrumb-divider: '';
    --bs-breadcrumb-font-size: .75rem;
    --bs-breadcrumb-padding-x: 1rem;
    --bs-breadcrumb-padding-y: .5rem;
    --bs-breadcrumb-margin-bottom: 0;
    color: #1f262b;
}
.system-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    mask-image: url("data:image/svg+xml;charset=UTF-8,<svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 28 28'><path d='M9.297 15c0 0.125-0.063 0.266-0.156 0.359l-7.281 7.281c-0.094 0.094-0.234 0.156-0.359 0.156s-0.266-0.063-0.359-0.156l-0.781-0.781c-0.094-0.094-0.156-0.219-0.156-0.359 0-0.125 0.063-0.266 0.156-0.359l6.141-6.141-6.141-6.141c-0.094-0.094-0.156-0.234-0.156-0.359s0.063-0.266 0.156-0.359l0.781-0.781c0.094-0.094 0.234-0.156 0.359-0.156s0.266 0.063 0.359 0.156l7.281 7.281c0.094 0.094 0.156 0.234 0.156 0.359z'/></svg>");
    background: #1f262b;
    width: 12px;
    height: 12px;
    mask-position: left center;
    mask-repeat: no-repeat;
    mask-size: cover;
    margin-top: 3px;
}
.system-breadcrumb .breadcrumb-item a {
    color: #9d4700;
    text-decoration: none;
}
.system-breadcrumb .breadcrumb-item a:hover {
    color: #bf5700;
    text-decoration: underline;
}

@media (max-width: 991px) {
    .section-title { font-size: 2rem; }
    .main-nav .nav-link { padding: 0.75rem 0 !important; border-bottom: none; }
}
@media (max-width: 575px) {
    section { padding: 3rem 0; }
}

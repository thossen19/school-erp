<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School') }} - What Starts Here Changes Everything</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @include('partials.homepage-styles')

        /* Hero */
        .hero {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c7f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat;
            opacity: 0.2;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26,26,46,0.92) 0%, rgba(15,52,96,0.85) 100%);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 4rem 0;
        }
        .hero h1 {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .hero h1 .highlight {
            color: var(--burnt-orange);
        }
        .hero .hero-sub {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.7);
            font-weight: 300;
            max-width: 600px;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        .hero .btn-hero {
            background: var(--burnt-orange);
            color: #fff;
            font-weight: 600;
            padding: 0.85rem 2.5rem;
            border-radius: 0;
            font-size: 1rem;
            border: 2px solid var(--burnt-orange);
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .hero .btn-hero:hover {
            background: transparent;
            color: #fff;
            border-color: #fff;
        }
        .hero .btn-hero-outline {
            background: transparent;
            color: #fff;
            font-weight: 500;
            padding: 0.85rem 2.5rem;
            border-radius: 0;
            font-size: 1rem;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s;
            margin-left: 1rem;
        }
        .hero .btn-hero-outline:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .hero-stat {
            color: rgba(255,255,255,0.6);
            margin-top: 3rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 2rem;
        }
        .hero-stat .num {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            display: block;
        }
        .hero-stat .label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Split Content Sections */
        .split-section {
            display: flex;
            align-items: center;
            gap: 4rem;
        }
        .split-section .content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 1.5rem;
        }
        .split-section .content p {
            color: #555;
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .split-section .stat-box {
            background: var(--burnt-orange);
            color: #fff;
            padding: 2rem;
            text-align: center;
        }
        .split-section .stat-box .num {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3rem;
            font-weight: 700;
            display: block;
        }
        .split-section .stat-box .label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
        }
        .split-section .stat-box-alt {
            background: var(--charcoal);
            color: #fff;
            padding: 2rem;
            text-align: center;
        }
        .split-section .stat-box-alt .num {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3rem;
            font-weight: 700;
            display: block;
        }
        .split-section .stat-box-alt .label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
        }
        .img-placeholder {
            background: var(--medium-gray);
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 1.2rem;
            overflow: hidden;
        }
        .img-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* News / Blog Cards */
        .news-card {
            border: none;
            border-radius: 0;
            overflow: hidden;
            transition: all 0.3s;
            background: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        }
        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        }
        .news-card .card-img-top {
            height: 220px;
            object-fit: cover;
        }
        .news-card .card-body { padding: 1.5rem; }
        .news-card .card-text {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* CTA Banner */
        .cta-banner {
            background: linear-gradient(135deg, var(--charcoal) 0%, #1a1a2e 100%);
            color: #fff;
            padding: 4rem 0;
            text-align: center;
        }
        .cta-banner h2 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .cta-banner p {
            color: rgba(255,255,255,0.7);
            font-size: 1.15rem;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        @media (max-width: 991px) {
            .hero h1 { font-size: 3rem; }
            .split-section { flex-direction: column; gap: 2rem; }
            .split-section.reverse { flex-direction: column-reverse; }
            .hero { min-height: 70vh; }
            .hero .btn-hero-outline { margin-left: 0; margin-top: 0.5rem; }
        }
        @media (max-width: 575px) {
            .hero h1 { font-size: 2.2rem; }
        }
    </style>
</head>
@php
    $menuItems = DB::table('menu_items')->where('school_id', 1)->where('is_active', true)->orderBy('order')->get();
    $utilityItems = $menuItems->where('menu_type', 'utility_bar');
    $headerItems = $menuItems->where('menu_type', 'header')->whereNull('parent_id');
    $headerChildren = $menuItems->where('menu_type', 'header')->whereNotNull('parent_id');
    $footerColumns = $menuItems->where('menu_type', 'footer')->whereNull('parent_id');
    $footerChildren = $menuItems->where('menu_type', 'footer')->whereNotNull('parent_id');
    $socialItems = $menuItems->where('menu_type', 'social');
    $school = DB::table('schools')->where('id', 1)->first(['name', 'logo']);
@endphp

<body>

    @include('partials.homepage-header')

    @include('partials.public-breadcrumb')

    <!-- ==================== HERO ==================== -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>
                        What Starts Here<br>
                        <span class="highlight">Changes Everything</span>
                    </h1>
                    <p class="hero-sub">
                        At {{ config('app.name', 'AISchool') }}, we nurture minds, spark curiosity, and build tomorrow's leaders. 
                        A world-class education rooted in excellence, innovation, and purpose.
                    </p>
                    <div>
                        <a href="#" class="btn btn-hero"><i class="fas fa-graduation-cap me-2"></i>Explore Programs</a>
                        <a href="#" class="btn btn-hero-outline"><i class="fas fa-play-circle me-2"></i>Virtual Tour</a>
                    </div>
                    <div class="hero-stat">
                        <div class="row g-4">
                            <div class="col-4"><span class="num">5,000+</span><span class="label">Students</span></div>
                            <div class="col-4"><span class="num">500+</span><span class="label">Faculty</span></div>
                            <div class="col-4"><span class="num">98%</span><span class="label">Graduation Rate</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FEATURES / ACADEMICS ==================== -->
    <section>
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Boundless Ambition<br class="d-md-none"> With Purpose</h2>
                <p class="section-subtitle mx-auto">
                    From classrooms to laboratories, studios to stadiums — what starts here doesn't just set a high standard. It raises it.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-book-open"></i></div>
                        <h5>Academic Excellence</h5>
                        <p>Rigorous curriculum designed to challenge and inspire. Our students consistently achieve top rankings nationally.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-flask"></i></div>
                        <h5>Research & Innovation</h5>
                        <p>Hands-on discovery across every discipline. Our labs and studios are where breakthrough ideas come to life.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h5>Vibrant Community</h5>
                        <p>A diverse, inclusive campus where every voice matters. 50+ clubs, organizations, and student-led initiatives.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-globe-americas"></i></div>
                        <h5>Global Impact</h5>
                        <p>Our alumni network spans 100+ countries, driving change in every field — from technology to public service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SPLIT: THE EXPERIENCE ==================== -->
    <section class="section-bg-light">
        <div class="container">
            <div class="split-section">
                <div class="col-lg-6 content">
                    <h2>The {{ config('app.name', 'AISchool') }} Experience</h2>
                    <p>
                        A better way, a better outcome, a better world. The {{ config('app.name', 'AISchool') }} experience reflects 
                        the spirit of excellence itself: bold, ambitious, always striving. 
                        Our students dive into hands-on learning, solve real-world challenges, and shape their own paths 
                        in an environment known for innovation and creativity.
                    </p>
                    <p>
                        With world-class faculty, cutting-edge facilities, and a campus that feels like home, 
                        we prepare our students not just for college — but for life.
                    </p>
                    <a href="#" class="btn btn-orange rounded-0 px-4 py-2">Be a Student <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-box">
                                <span class="num">#1</span>
                                <span class="label">Public School in Region</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box-alt">
                                <span class="num">50+</span>
                                <span class="label">Programs of Study</span>
                            </div>
                        </div>
                        <div class="col-6 mt-3">
                            <div class="stat-box-alt">
                                <span class="num">15:1</span>
                                <span class="label">Student-Faculty Ratio</span>
                            </div>
                        </div>
                        <div class="col-6 mt-3">
                            <div class="stat-box">
                                <span class="num">95%</span>
                                <span class="label">College Acceptance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SPLIT: BIG IDEAS ==================== -->
    <section>
        <div class="container">
            <div class="split-section reverse">
                <div class="col-lg-6">
                    <div class="img-placeholder">
                        <img src="https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=928&q=80" alt="Students collaborating">
                    </div>
                </div>
                <div class="col-lg-6 content">
                    <h2>Big Ideas.<br>Bigger Impact.</h2>
                    <p>
                        From advancing health care to redefining industries, our researchers tackle the problems that matter most. 
                        Our faculty and students make breakthrough discoveries that earn recognition, transform lives, 
                        and create a ripple effect felt across our community and beyond.
                    </p>
                    <div class="row g-3 mt-4">
                        <div class="col-4 text-center">
                            <div class="fw-bold text-orange" style="font-size:2rem;color:var(--burnt-orange);font-family:'Playfair Display',serif;">50+</div>
                            <small class="text-muted">Research Centers</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold" style="font-size:2rem;color:var(--burnt-orange);font-family:'Playfair Display',serif;">$10M+</div>
                            <small class="text-muted">Annual Research Funding</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold" style="font-size:2rem;color:var(--burnt-orange);font-family:'Playfair Display',serif;">300+</div>
                            <small class="text-muted">Published Papers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== NEWS ==================== -->
    <section class="section-bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="section-title mb-1">Latest News</h2>
                    <p class="section-subtitle mb-0">Discover what's happening on campus</p>
                </div>
                <a href="#" class="btn btn-outline-orange rounded-0 d-none d-md-inline-flex">All News <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Science lab">
                        <div class="card-body">
                            <small class="text-orange fw-semibold" style="color:var(--burnt-orange)">Research</small>
                            <h5 class="mt-1 fw-semibold">Students Win National Science Competition</h5>
                            <p class="card-text">A team of three students from our school won first place for their innovative renewable energy project.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c7f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Campus">
                        <div class="card-body">
                            <small class="text-orange fw-semibold" style="color:var(--burnt-orange)">Campus Life</small>
                            <h5 class="mt-1 fw-semibold">New Innovation Center Breaks Ground</h5>
                            <p class="card-text">A state-of-the-art innovation center will open next fall, featuring collaborative labs and maker spaces.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="news-card">
                        <img src="https://images.unsplash.com/photo-1522885147691-064d935573ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Arts">
                        <div class="card-body">
                            <small class="text-orange fw-semibold" style="color:var(--burnt-orange)">Arts & Culture</small>
                            <h5 class="mt-1 fw-semibold">Annual Spring Festival Draws Record Crowd</h5>
                            <p class="card-text">Our annual Spring Festival showcased student talent in music, dance, and visual arts with over 2,000 attendees.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOR TEXAS / CTA ==================== -->
    <div class="cta-banner">
        <div class="container">
            <h2>For {{ config('app.name', 'Our Community') }},<br>For the Future</h2>
            <p>
                As a flagship institution, we exist first and foremost to serve our community. 
                We fuel innovation, develop tomorrow's leaders, and partner to create a stronger, brighter future for all.
            </p>
            <a href="#" class="btn btn-hero me-0 me-md-2"><i class="fas fa-hand-holding-heart me-2"></i>Support Our Mission</a>
            <a href="#" class="btn btn-hero-outline mt-2 mt-md-0"><i class="fas fa-calendar-check me-2"></i>Schedule a Visit</a>
        </div>
    </div>

    @include('partials.homepage-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
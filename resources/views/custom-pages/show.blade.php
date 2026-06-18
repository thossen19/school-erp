@php
    $menuItems = DB::table('menu_items')->where('school_id', 1)->where('is_active', true)->orderBy('order')->get();
    $utilityItems = $menuItems->where('menu_type', 'utility_bar');
    $headerItems = $menuItems->where('menu_type', 'header')->whereNull('parent_id');
    $headerChildren = $menuItems->where('menu_type', 'header')->whereNotNull('parent_id');
    $footerColumns = $menuItems->where('menu_type', 'footer')->whereNull('parent_id');
    $footerChildren = $menuItems->where('menu_type', 'footer')->whereNotNull('parent_id');
    $socialItems = $menuItems->where('menu_type', 'social');
    $school = DB::table('schools')->where('id', 1)->first(['name', 'logo']);
    $classes = DB::table('classes')->where('school_id', 1)->orderBy('name')->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }} - {{ config('app.name') }}</title>
    <meta name="description" content="{{ $page->meta_description ?? '' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @include('partials.homepage-styles')

        .section-block { padding: 4rem 0; }
        .section-block.bg-light { background: var(--light-gray); }
        .section-block.bg-dark { background: #1a1a2e; color: #fff; }
    </style>
</head>
<body>

    @include('partials.homepage-header')

    @include('partials.public-breadcrumb')

    @foreach($sections as $section)
        @php $content = json_decode($section->content); @endphp

        @if($section->section_type === 'hero')
            <section class="section-block" style="background:{{ $content->background_color ?? '#1a1a2e' }};color:#fff;min-height:50vh;display:flex;align-items:center">
                <div class="container text-center">
                    <h1 class="display-4 fw-bold">{{ $content->heading ?? '' }}</h1>
                    @if(!empty($content->subheading))
                        <p class="lead mb-4" style="opacity:0.8">{{ $content->subheading }}</p>
                    @endif
                    @if(!empty($content->button_text))
                        <a href="{{ $content->button_url ?? '#' }}" class="btn btn-lg px-4" style="background:#BF5700;color:#fff;border-radius:0">{{ $content->button_text }}</a>
                    @endif
                </div>
            </section>

        @elseif($section->section_type === 'text')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="mb-4">{{ $content->title }}</h2>@endif
                    <div>{!! $content->content ?? '' !!}</div>
                </div>
            </section>

        @elseif($section->section_type === 'image_text')
            @php
                $imgPos = $content->image_position ?? 'left';
                $imgSize = $content->image_size ?? 'full_width';
                $imgClass = $imgSize === 'half_width' ? '' : ($imgSize === 'original' ? 'w-auto' : '');
                $imgStyle = $imgSize === 'full_width' ? 'width:1024px;height:743px;object-fit:cover;margin:0 auto;display:block' : ($imgSize === 'half_width' ? 'width:50%' : '');
            @endphp
            <section class="section-block bg-light">
                <div class="container">
                    <div class="row align-items-center g-5 {{ $imgPos === 'center' ? 'justify-content-center text-center' : '' }}">
                        @if($imgPos !== 'center')
                        <div class="col-lg-6 {{ $imgPos === 'right' ? 'order-lg-2' : '' }}">
                        @else
                        <div class="col-lg-8">
                        @endif
                            @if(!empty($content->image_url))
                                <img src="{{ $content->image_url }}" alt="" class="img-fluid rounded shadow-sm {{ $imgClass }}" style="{{ $imgStyle }}">
                            @endif
                        </div>
                        @if($imgPos !== 'center')
                        <div class="col-lg-6">
                        @else
                        <div class="col-lg-8 mt-4">
                        @endif
                            @if(!empty($content->title))<h2 class="mb-3">{{ $content->title }}</h2>@endif
                            <div>{!! $content->content ?? '' !!}</div>
                        </div>
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'features')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4">
                        @foreach($content->items ?? [] as $item)
                            <div class="col-md-4 text-center">
                                <div class="mb-3" style="font-size:2.5rem;color:#BF5700"><i class="fas {{ $item->icon ?? 'fa-star' }}"></i></div>
                                <h5>{{ $item->title ?? '' }}</h5>
                                <p class="text-muted">{{ $item->description ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'cards')
            <section class="section-block bg-light">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4">
                        @foreach($content->cards ?? [] as $card)
                            <div class="col-md-{{ $content->columns ? 12 / min($content->columns, 4) : 4 }}">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5>{{ $card->title ?? '' }}</h5>
                                        <p class="text-muted mb-0">{{ $card->content ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'cta')
            <section class="section-block" style="background:{{ $content->background_color ?? '#BF5700' }};color:#fff">
                <div class="container text-center">
                    <h2 class="fw-bold">{{ $content->heading ?? '' }}</h2>
                    @if(!empty($content->description))<p class="lead mb-4" style="opacity:0.9">{{ $content->description }}</p>@endif
                    @if(!empty($content->button_text))
                        <a href="{{ $content->button_url ?? '#' }}" class="btn btn-lg btn-light px-4 fw-semibold">{{ $content->button_text }}</a>
                    @endif
                </div>
            </section>

        @elseif($section->section_type === 'faq')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="accordion" id="faqAccordion">
                        @foreach($content->items ?? [] as $i => $item)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                        {{ $item->question ?? '' }}
                                    </button>
                                </h2>
                                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">{{ $item->answer ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'contact')
            <section class="section-block bg-light">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row justify-content-center g-4">
                        @if(!empty($content->email))
                            <div class="col-md-4 text-center"><i class="fas fa-envelope fa-2x mb-2" style="color:#BF5700"></i><p class="mb-0">{{ $content->email }}</p></div>
                        @endif
                        @if(!empty($content->phone))
                            <div class="col-md-4 text-center"><i class="fas fa-phone fa-2x mb-2" style="color:#BF5700"></i><p class="mb-0">{{ $content->phone }}</p></div>
                        @endif
                        @if(!empty($content->address))
                            <div class="col-md-4 text-center"><i class="fas fa-map-marker-alt fa-2x mb-2" style="color:#BF5700"></i><p class="mb-0">{{ $content->address }}</p></div>
                        @endif
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'pricing')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4 justify-content-center">
                        @foreach($content->plans ?? [] as $plan)
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body p-4">
                                        <h4 class="fw-bold">{{ $plan->name ?? '' }}</h4>
                                        <h2 class="display-6 fw-bold my-3" style="color:#BF5700">{{ $plan->price ?? '' }}</h2>
                                        <p class="text-muted">{{ $plan->features ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'team')
            <section class="section-block bg-light">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4 justify-content-center">
                        @foreach($content->members ?? [] as $member)
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    @if(!empty($member->image_url))
                                        <img src="{{ $member->image_url }}" alt="{{ $member->name ?? '' }}" class="rounded-circle" style="width:120px;height:120px;object-fit:cover">
                                    @else
                                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;color:#fff;font-size:2.5rem">{{ substr($member->name ?? '?', 0, 1) }}</div>
                                    @endif
                                </div>
                                <h5 class="mb-1">{{ $member->name ?? '' }}</h5>
                                <small class="text-muted">{{ $member->designation ?? '' }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'testimonials')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4">
                        @foreach($content->items ?? [] as $item)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="mb-2" style="color:#ffc107">
                                            @for($r = 1; $r <= 5; $r++)
                                                <i class="fas fa-star{{ $r <= ($item->rating ?? 5) ? '' : '-o text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-2"><em>"{{ $item->text ?? '' }}"</em></p>
                                        <small class="fw-semibold">{{ $item->name ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'stats')
            <section class="section-block" style="background:#333F48;color:#fff">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row text-center g-4">
                        @foreach($content->items ?? [] as $item)
                            <div class="col-md-3">
                                <div class="display-5 fw-bold" style="color:#BF5700">{{ $item->value ?? '' }}</div>
                                <div class="mt-1" style="opacity:0.7">{{ $item->label ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'video')
            <section class="section-block">
                <div class="container text-center">
                    @if(!empty($content->title))<h2 class="mb-4">{{ $content->title }}</h2>@endif
                    <div class="ratio ratio-16x9" style="max-width:800px;margin:auto">
                        <iframe src="{{ $content->url ?? '' }}" allowfullscreen></iframe>
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'image')
            <section class="section-block text-center">
                <div class="container">
                    @if(!empty($content->image_url))
                        <img src="{{ $content->image_url }}" alt="{{ $content->alt_text ?? '' }}" style="max-width:100%;height:auto">
                    @endif
                    @if(!empty($content->caption))
                        <p class="mt-3 text-muted">{{ $content->caption }}</p>
                    @endif
                </div>
            </section>

        @elseif($section->section_type === 'divider')
            <div style="padding:1rem 0;text-align:center">
                <hr style="border-color:{{ $content->color ?? '#e5e5e5' }};border-width:{{ $content->height ?? '1px' }};margin:auto;max-width:80%">
            </div>

        @elseif($section->section_type === 'html')
            <section class="section-block">
                <div class="container">
                    {!! $content->html ?? '' !!}
                </div>
            </section>

        @elseif($section->section_type === 'gallery')
            <section class="section-block bg-light">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-3">
                        @foreach($content->images ?? [] as $image)
                            <div class="col-md-4">
                                <img src="{{ $image }}" alt="" class="img-fluid rounded shadow-sm" style="height:200px;width:100%;object-fit:cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'partners')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-5">{{ $content->title }}</h2>@endif
                    <div class="row g-4 align-items-center justify-content-center">
                        @foreach($content->logos ?? [] as $logo)
                            <div class="col-4 col-md-2">
                                <img src="{{ $logo }}" alt="" class="img-fluid" style="max-height:60px;filter:grayscale(1);opacity:0.6">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif($section->section_type === 'newsletter')
            <section class="section-block bg-light">
                <div class="container text-center">
                    @if(!empty($content->title))<h2 class="mb-3">{{ $content->title }}</h2>@endif
                    <form class="row g-2 justify-content-center" style="max-width:500px;margin:auto">
                        <div class="col-8">
                            <input type="email" class="form-control" placeholder="{{ $content->placeholder ?? 'Your email' }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn" style="background:#BF5700;color:#fff">{{ $content->button_text ?? 'Subscribe' }}</button>
                        </div>
                    </form>
                </div>
            </section>

        @elseif($section->section_type === 'application_form')
            <section class="section-block">
                <div class="container">
                    @if(!empty($content->title))<h2 class="text-center mb-3">{{ $content->title }}</h2>@endif
                    @if(!empty($content->description))<p class="text-center text-muted mb-4">{{ $content->description }}</p>@endif
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
                            @endif
                            <form method="POST" action="{{ route('public.application-form.submit') }}" enctype="multipart/form-data" class="border p-4 bg-light">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Applicant Name <span class="text-danger">*</span></label>
                                        <input type="text" name="applicant_name" class="form-control" required value="{{ old('applicant_name') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="date_of_birth" class="form-control" required value="{{ old('date_of_birth') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Birth Cert. No.</label>
                                        <input type="text" name="birth_cert_no" class="form-control" value="{{ old('birth_cert_no') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select</option>
                                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                                            <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Applying for Class</label>
                                        <select name="class_applying_for_id" class="form-select">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ old('class_applying_for_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Photo</label>
                                        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(event)">
                                        <img id="photoPreview" class="mt-2 rounded" style="max-height:120px;display:none">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Address</label>
                                        <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Father's Name</label>
                                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Father's Phone</label>
                                        <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mother's Name</label>
                                        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mother's Phone</label>
                                        <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Previous School</label>
                                        <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school') }}">
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-lg px-5" style="background:#BF5700;color:#fff;border-radius:0">
                                            <i class="fas fa-paper-plane me-2"></i>{{ $content->button_text ?? 'Submit Application' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    @include('partials.homepage-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function previewPhoto(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                var img = document.getElementById('photoPreview');
                img.src = ev.target.result;
                img.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    }
    </script>
</body>
</html>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">
                    @if($school->logo ?? false)
                        <img src="{{ asset('storage/'.$school->logo) }}" alt="{{ $school->name }}" height="32">
                    @else
                        {{ config('app.name', 'AISchool') }}
                        <small>What Starts Here</small>
                    @endif
                </div>
                <p style="color:rgba(255,255,255,0.5);font-size:0.85rem;line-height:1.7;">
                    Excellence in education since 2000. We prepare students to thrive in a rapidly changing world 
                    through a commitment to academic rigor, character development, and community engagement.
                </p>
                <div class="social mt-3">
                    @foreach($socialItems as $sItem)
                    <a href="{{ $sItem->url ?: '#' }}" aria-label="{{ $sItem->label }}"><i class="{{ $sItem->icon ?: 'fas fa-link' }}"></i></a>
                    @endforeach
                </div>
            </div>
            @foreach($footerColumns as $col)
            <div class="col-6 col-lg-2">
                <h6>{{ $col->label }}</h6>
                @foreach($footerChildren->where('parent_id', $col->id) as $link)
                <a href="{{ $link->url ?: '#' }}">{{ $link->label }}</a>
                @endforeach
            </div>
            @endforeach
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
            <span>&copy; {{ date('Y') }} {{ config('app.name', 'AISchool') }}. All rights reserved.</span>
            <span class="mt-2 mt-md-0">
                <i class="fas fa-heart" style="color:var(--burnt-orange)"></i> What Starts Here Changes Everything
            </span>
        </div>
    </div>
</footer>

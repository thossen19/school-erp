<div class="container-xl">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-labelledby="system-breadcrumb" class="d-inline-block">
                <h2 id="system-breadcrumb" class="visually-hidden">Breadcrumb</h2>
                <ol class="breadcrumb border system-breadcrumb">
                    @if(request()->path() === '/')
                    <li class="breadcrumb-item" aria-current="page">Home</li>
                    @else
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" style="color:#1f262b">
                        {{ $breadcrumbTitle ?? ($page->title ?? '') }}
                    </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>

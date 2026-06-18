<div class="utility-bar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            @foreach($utilityItems->take(4) as $item)
            <a href="{{ $item->url ?: '#' }}"><i class="fas {{ $item->icon ?: 'fa-link' }} me-1"></i>{{ $item->label }}</a>
            @endforeach
        </div>
        <div>
            @foreach($utilityItems->skip(4) as $item)
            <a href="{{ $item->url ?: '#' }}"><i class="fas {{ $item->icon ?: 'fa-link' }} me-1"></i>{{ $item->label }}</a>
            @endforeach
        </div>
    </div>
</div>

<nav class="main-nav navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            @if($school->logo ?? false)
                <img src="{{ asset('storage/'.$school->logo) }}" alt="{{ $school->name }}" height="80">
            @else
                {{ config('app.name', 'AISchool') }}
                <small>What Starts Here</small>
            @endif
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                @foreach($headerItems as $hItem)
                    @php
                        $subItems = $headerChildren->where('parent_id', $hItem->id);
                        $itemPath = trim(parse_url($hItem->url ?: '', PHP_URL_PATH) ?: $hItem->url ?: '', '/');
                        $isActive = $hItem->url && $hItem->url !== '#' && request()->path() === $itemPath;
                        if (!$isActive && $subItems->isNotEmpty()) {
                            foreach ($subItems as $child) {
                                $childPath = trim(parse_url($child->url ?: '', PHP_URL_PATH) ?: $child->url ?: '', '/');
                                if (request()->path() === $childPath) { $isActive = true; break; }
                            }
                        }
                    @endphp
                    @if($subItems->isNotEmpty())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}" href="{{ $hItem->url ?: '#' }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if($hItem->icon)<i class="fas {{ $hItem->icon }} me-1"></i>@endif{{ $hItem->label }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($subItems as $child)
                            @php $childActive = request()->path() === trim(parse_url($child->url ?: '', PHP_URL_PATH) ?: $child->url ?: '', '/'); @endphp
                            <li><a class="dropdown-item {{ $childActive ? 'active' : '' }}" href="{{ $child->url ?: '#' }}">@if($child->icon)<i class="fas {{ $child->icon }} me-1"></i>@endif{{ $child->label }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $hItem->url ?: '#' }}">
                            @if($hItem->icon)<i class="fas {{ $hItem->icon }} me-1"></i>@endif{{ $hItem->label }}
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
            @auth
            <div class="ms-3">
                <a href="{{ url('/dashboard') }}" class="btn btn-orange"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            </div>
            @endauth
        </div>
    </div>
</nav>

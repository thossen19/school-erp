<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $value = '',
        public string $icon = 'fa-chart-bar',
        public string $color = 'primary',
        public string $trend = '',
        public bool $trendUp = true,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.stats-card');
    }
}

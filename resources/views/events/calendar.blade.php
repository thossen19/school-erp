@extends('layouts.app')
@section('title', 'Calendar Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar me-2"></i>Calendar Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Calendar</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('events.calendar', ['month' => $month-1 < 1 ? 12 : $month-1, 'year' => $month-1 < 1 ? $year-1 : $year]) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
                <h5 class="fw-semibold mb-0">{{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h5>
                <a href="{{ route('events.calendar', ['month' => $month+1 > 12 ? 1 : $month+1, 'year' => $month+1 > 12 ? $year+1 : $year]) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
            </div>
            <a href="{{ route('events.calendar') }}" class="btn btn-sm btn-outline-primary">Today</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center mb-0">
                <thead class="table-light">
                    <tr><th style="width:14.28%">Sun</th><th style="width:14.28%">Mon</th><th style="width:14.28%">Tue</th><th style="width:14.28%">Wed</th><th style="width:14.28%">Thu</th><th style="width:14.28%">Fri</th><th style="width:14.28%">Sat</th></tr>
                </thead>
                <tbody>
                    @php
                        $startOfMonth = Carbon\Carbon::create($year, $month, 1);
                        $endOfMonth = $startOfMonth->copy()->endOfMonth();
                        $startDay = $startOfMonth->dayOfWeek;
                        $daysInMonth = $startOfMonth->daysInMonth;
                        $today = now()->format('Y-m-d');
                        $eventsByDay = $events->groupBy(function($e) { return \Carbon\Carbon::parse($e->start_date)->day; });
                    @endphp
                    <tr>
                        @for($i = 0; $i < $startDay; $i++)
                            <td style="height:90px;vertical-align:top;padding:4px;background:#f8f9fa"></td>
                        @endfor
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $cellDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $isToday = $cellDate == $today;
                                $dayEvents = $eventsByDay->get($day) ?? collect([]);
                            @endphp
                            <td style="height:90px;vertical-align:top;padding:4px;{{ $isToday ? 'background:#e7f1ff' : '' }}">
                                <small class="fw-bold {{ $isToday ? 'text-primary' : '' }}">{{ $day }}</small>
                                @foreach($dayEvents as $ev)
                                    <div class="mt-1"><span class="badge bg-{{ $ev->status=='completed'?'success':($ev->status=='ongoing'?'warning':($ev->status=='cancelled'?'danger':'primary')) }}" style="font-size:0.55rem;cursor:pointer" title="{{ $ev->title }}">{{ \Str::limit($ev->title, 12) }}</span></div>
                                @endforeach
                            </td>
                            @if(($day + $startDay) % 7 == 0 && $day < $daysInMonth)
                                </tr><tr>
                            @endif
                        @endfor
                        @php $remaining = (7 - (($daysInMonth + $startDay) % 7)) % 7; @endphp
                        @for($i = 0; $i < $remaining; $i++)
                            <td style="height:90px;vertical-align:top;padding:4px;background:#f8f9fa"></td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Events This Month</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['Date','Event','Type','Venue','Status']">
            @forelse($events as $event)
            <tr>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d') }}</td>
                <td class="fw-semibold">{{ $event->title }}</td>
                <td><span class="badge bg-info">{{ ucfirst($event->event_type) }}</span></td>
                <td>{{ $event->venue ?? '-' }}</td>
                <td><span class="badge bg-{{ $event->status=='completed'?'success':($event->status=='ongoing'?'warning':($event->status=='cancelled'?'danger':'primary')) }}">{{ ucfirst($event->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No events this month</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bell me-2"></i>Notification Center</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Notifications</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success"><i class="fas fa-check-double me-1"></i>Mark All Read</button>
        <button class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i>Clear All</button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach(range(1,10) as $i)
            <div class="list-group-item list-group-item-action d-flex gap-3 py-3 {{ $i<=3?'':'bg-transparent' }}">
                <div class="avatar-circle bg-{{ ['primary','success','warning','danger','info'][$i%5] }} text-white" style="width:40px;height:40px;flex-shrink:0">
                    <i class="fas fa-{{ ['user-plus','credit-card','calendar','exclamation-triangle','trophy','book','bus','money-bill','users','file'][$i-1] }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="fw-semibold d-block">{{ ['New Student Enrolled','Fee Payment Received','Event Reminder','Leave Request','Achievement Unlocked','Book Due Reminder','Route Changed','Invoice Generated','Meeting Scheduled','Report Ready'][$i-1] }}</small>
                            <small class="text-muted d-block">{{ ['Student admitted to Grade 10','$500 received from Jane Smith','Annual Sports Day tomorrow','Sarah Wilson requested sick leave','John won Science Fair','Library book overdue','Bus route updated for Route A','Fee invoice for June 2026','Staff meeting Friday 3PM','Monthly report generated'][$i-1] }}</small>
                        </div>
                        <small class="text-muted text-nowrap ms-3">{{ $i<=3?'5 mins ago':($i<=6?'2 hours ago':'Yesterday') }}</small>
                    </div>
                </div>
                @if($i<=3)<div class="mt-1"><span class="badge bg-primary rounded-pill" style="width:8px;height:8px;padding:0">&nbsp;</span></div>@endif
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    <nav><ul class="pagination pagination-sm">
        <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
    </ul></nav>
</div>
@endsection
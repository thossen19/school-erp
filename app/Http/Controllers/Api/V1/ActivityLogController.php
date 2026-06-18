<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $logs = \Spatie\Activitylog\Models\Activity::with('causer')->when($request->log_name, fn($q) => $q->where('log_name', $request->log_name))->when($request->event, fn($q) => $q->where('event', $request->event))->when($request->subject_type, fn($q) => $q->where('subject_type', $request->subject_type))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 25);

        return $this->paginatedResponse($logs, 'Activity logs retrieved');
    }

    public function getByUser(int $userId, Request $request): JsonResponse
    {
        $logs = \Spatie\Activitylog\Models\Activity::with('causer')->where('causer_id', $userId)->when($request->log_name, fn($q) => $q->where('log_name', $request->log_name))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 25);

        return $this->paginatedResponse($logs, 'User activity logs');
    }

    public function getByModule(string $module, Request $request): JsonResponse
    {
        $logs = \Spatie\Activitylog\Models\Activity::with('causer')->where('log_name', $module)->when($request->event, fn($q) => $q->where('event', $request->event))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 25);

        return $this->paginatedResponse($logs, 'Module activity logs');
    }
}

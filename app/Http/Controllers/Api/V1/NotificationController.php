<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->unread_only, fn($q) => $q->whereNull('read_at'))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return $this->paginatedResponse($notifications, 'Notifications retrieved');
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $this->successResponse($notification, 'Notification marked as read');
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();
        return $this->successResponse(null, 'All notifications marked as read');
    }

    public function getUnreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();
        return $this->successResponse(['unread_count' => $count], 'Unread count retrieved');
    }
}

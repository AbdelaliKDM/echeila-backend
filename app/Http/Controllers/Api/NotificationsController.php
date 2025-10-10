<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;


class NotificationsController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $user = auth()->user();
      $notifications = $user->notifications()->orderBy('created_at', 'desc');
      if (request()->boolean('paginate')) {
        $notifications = $notifications->paginate($request->get('per_page', 10));
      } else {
        $notifications = $notifications->get();
      }
      return $this->successResponse(
        data: NotificationResource::collection($notifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function unread(Request $request)
  {
    try {
      $user = auth()->user();
      $unreadNotifications = $user->unreadNotifications()->orderBy('created_at', 'desc');

      if (request()->boolean('paginate')) {
        $unreadNotifications = $unreadNotifications->paginate($request->get('per_page', 10));
      } else {
        $unreadNotifications = $unreadNotifications->get();
      }

      return $this->successResponse(
        data: NotificationResource::collection($unreadNotifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function markAsRead($id, Request $request)
  {
    try {
      $user = auth()->user();
      $notification = $user->notifications()->findOrFail($id);
      $notification->markAsRead();
      return $this->successResponse(
        message: 'Notification marked as read successfully.',
        data: new NotificationResource($notification)
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }
}

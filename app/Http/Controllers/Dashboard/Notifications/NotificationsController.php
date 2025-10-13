<?php

namespace App\Http\Controllers\Dashboard\Notifications;

use App\Constants\NotificationMessages;
use App\Constants\UserStatus;
use App\Datatables\RoleDatatable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_SETTINGS)) {
      return redirect()->route('unauthorized');
    }
    return view("dashboard.notification.send");
  }

  public function send(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_SETTINGS)) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'channels' => ['required', 'array'],
      'channels.*' => ['required', 'string', 'in:database,fcm'],
      'key' => ['required', 'string', 'in:' . implode(',', NotificationMessages::customNotifications())],
      'title' => ['required', 'array'],
      'body' => ['required', 'array'],
      'title.en' => ['required', 'string'],
      'title.ar' => ['required', 'string'],
      'title.fr' => ['required', 'string'],
      'body.en' => ['required', 'string'],
      'body.ar' => ['required', 'string'],
      'body.fr' => ['required', 'string'],
    ]);

    // Send notifications logic here
    $users = User::where('status', UserStatus::ACTIVE)->get();

    foreach ($users as $user) {
      $user->notify(new NewMessageNotification(
        key: $data['key'],
        data: ['type' => $data['key']],
        channels: $data['channels'],
        custom_title: $data['title'],
        custom_body: $data['body']
      ));
    }

    return redirect()->back()->with('success', __('app.notification_sent_successfully'));
  }
}

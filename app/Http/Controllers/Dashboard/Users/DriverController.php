<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Models\User;
use App\Models\Driver;
use App\Constants\UserType;
use App\Constants\NotificationMessages;
use App\Http\Resources\SubscriptionResource;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\DB;
use App\Datatables\DriverDatatable;
use App\Http\Controllers\Controller;


class DriverController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }

    // Calculate statistics
    $stats = [
      'total' => User::drivers()->count(),
      'active' => User::drivers()->where('status', 'active')->count(),
      'banned' => User::drivers()->where('status', 'banned')->count(),
      'new' => User::drivers()->where('created_at', '>=', now()->subDays(7))->count(),
    ];

    $drivers = new DriverDatatable();
    if ($request->wantsJson()) {
      return $drivers->datatables($request);
    }
    return view("dashboard.driver.list")->with([
      "columns" => $drivers::columns(),
      "stats" => $stats,
    ]);
  }

  public function show($id)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }
    $driver = Driver::with([
    'user.wallet.transactions',
    'services',
    'subscription',
    'trips',
    'reviews'
])->where('user_id',$id)->first();

$stats = [
    'trips_count' => $driver->trips()->count(),
    'reviews_count' => $driver->reviews()->count(),
    'avg_rating' => $driver->reviews()->avg('rating') ?? 0,
    'transactions_count' => $driver->user->wallet->transactions()->count(),
    'services_count' => $driver->services()->count(),
    'total_earned' => $driver->trips()->join('trip_clients', 'trips.id', '=', 'trip_clients.trip_id')
                              ->sum('trip_clients.total_fees'),
];

$transactions = $driver->user->wallet->transactions()->latest()->paginate(15);
$recentTrips = $driver->trips()->latest()->paginate(10);
$reviews = $driver->reviews()->latest()->paginate(5);

return view('dashboard.driver.show', compact('driver', 'stats', 'transactions', 'recentTrips', 'reviews'));
  }

    public function updateStatus(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_DRIVERS)) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'id' => 'required|exists:users,id',
      'status' => 'required|string|in:approved,denied',
      'confirmed' => 'required|accepted',
    ]);

    try {
      DB::beginTransaction();
      $user = User::findOrFail($data['id']);
      $user->driver->update(['status' => $data['status']]);

      // Send notification
      $notificationKey = $data['status'] === 'approved' 
        ? NotificationMessages::DRIVER_APPROVED 
        : NotificationMessages::DRIVER_DENIED;
      
      $user->notify(new NewMessageNotification(
        key: $notificationKey,
        data: ['status' => $data['status']]
      ));

      DB::commit();

      $statusMessage = $data['status'] === 'approved'
        ? __('driver.approved_successfully')
        : __('driver.denied_successfully');

      return redirect()->back()->with('success', $statusMessage);
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function purchaseSubscription(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_DRIVERS)) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'id' => 'required|exists:users,id',
      'months' => 'required|integer|min:1',
    ]);

    // Fixed monthly fee
    $monthlyFee = 1000; // Change as needed

    try {
      DB::beginTransaction();
      $user = User::findOrFail($data['id']);
      $driver = $user->driver;
      
      if (!$driver) {
        throw new \Exception('User is not a driver');
      }

      $months = (int) $data['months'];
      $subscription = $driver->subscription;

      if ($subscription) {
        // Extend existing subscription
        $subscription->update(['end_date' => $subscription->end_date->copy()->addMonths($months)]);
      } else {
        // Create new subscription
        $subscription = $driver->subscriptions()->create([
          'start_date' => now(),
          'end_date' => now()->addMonths($months),
        ]);
      }

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::TRANSACTION_SUBSCRIPTION,
      ));

      DB::commit();
      return redirect()->back()->with('success', __('app.subscription_purchased_successfully'));
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

}

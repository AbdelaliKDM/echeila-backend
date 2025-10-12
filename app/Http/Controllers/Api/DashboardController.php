<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Driver;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\DriverStatus;
use App\Traits\ApiResponseTrait;
use App\Constants\TransactionType;
use App\Http\Controllers\Controller;
use App\Constants\NotificationMessages;
use App\Notifications\NewMessageNotification;

class DashboardController extends Controller
{
  use ApiResponseTrait;

  public function updateDriverStatus(Request $request)
  {
    $validated = $request->validate([
      'driver_id' => 'required|exists:drivers,id',
      'status' => 'required|in:'. implode(',', DriverStatus::all())
    ]);

    try {
      $driver = Driver::find($request->driver_id);
      $driver->update(['status' => $request->status]);

      // Send notification
      $driver->user->notify(new NewMessageNotification(
        $request->status == DriverStatus::APPROVED ?
         NotificationMessages::DRIVER_APPROVED :
           NotificationMessages::DRIVER_REJECTED,
        ['status' => $request->status]
      ));

      return $this->successResponse();
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode());
    }
  }

  public function chargeWallet(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'amount' => 'required|numeric|min:0'
    ]);

    try {
      $user = User::find($request->user_id);
      $wallet = $user->wallet;
      $wallet->increment('balance', $request->amount);

      Transaction::create([
        'wallet_id' => $wallet->id,
        'type' => TransactionType::DEPOSIT,
        'amount' => $request->amount,
      ]);

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::TRANSACTION_DEPOSIT,
        data: ['amount' => $request->amount, 'balance' => $wallet->balance],
        replace: ['amount' => $request->amount]
      ));

      return $this->successResponse();
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode());
    }
  }

  public function withdrawSum(Request $request)
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'amount' => 'required|numeric|min:0'
    ]);

    try {
      $user = User::find($request->user_id);
      $wallet = $user->wallet;
      
      if ($wallet->balance < $request->amount) {
        throw new Exception('Insufficient balance');
      }

      $wallet->decrement('balance', $request->amount);

      Transaction::create([
        'wallet_id' => $wallet->id,
        'type' => TransactionType::WITHDRAW,
        'amount' => $request->amount,
      ]);

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::TRANSACTION_WITHDRAW,
        data: ['amount' => $request->amount, 'balance' => $wallet->balance],
        replace: ['amount' => $request->amount]
      ));

      return $this->successResponse();
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode());
    }
  }
}
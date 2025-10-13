<?php

namespace App\Http\Controllers\Api;

use App\Constants\NotificationMessages;
use App\Constants\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Notifications\NewMessageNotification;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'months' => 'required|integer|min:1',
        ]);

        try {
            $user = auth()->user();
            $driver = $user->driver;
            if (! $driver) {
                throw new Exception('User is not a driver');
            }

            $wallet = $user->wallet;
            $months = (int) $request->months;
            $monthlyFee = 1000; // Change as needed
            $totalFee = $months * $monthlyFee;

            if ($wallet->balance < $totalFee) {
                throw new Exception('Insufficient wallet balance');
            }

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

            $wallet->decrement('balance', $totalFee);

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::SUBSCRIBTION,
                'amount' => -abs($totalFee)
            ]);

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_SUBSCRIPTION,
                data: ['amount' => $transaction->amount, 'balance' => $wallet->balance]
            ));

            return $this->successResponse(new SubscriptionResource($subscription));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}

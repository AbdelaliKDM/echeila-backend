<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $user = auth()->user();

      $wallet = $user->wallet;

      if (!$wallet) {
        throw new Exception('Wallet not found', 404);
      }

      $transactions = $wallet->transactions()->latest()->paginate(10);

      return $this->successResponse(
        data: TransactionResource::collection($transactions),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }
}
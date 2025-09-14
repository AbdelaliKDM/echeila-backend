<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Passenger;
use App\Traits\RandomTrait;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Traits\FirebaseTrait;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends Controller
{

  use ApiResponseTrait, FirebaseTrait, RandomTrait;

  public function register(RegisterRequest $request)
  {
    $this->validateRequest($request);

    try {

      $user = User::create([
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'device_token' => $request->device_token
      ]);

      Passenger::create([
        'user_id' => $user->id,
      ]);

      $user->load('passenger', 'driver', 'federation');

      return $this->successResponse(new UserResource($user));

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function login(LoginRequest $request)
  {
    $this->validateRequest($request);

    try {
      $firebase_user = $this->getFirebaseUser($request->id_token);
      $user = User::where('phone', $request->phone)->first();

      if (!$user) {
        throw new Exception('User not found', 404);
      }

      if ($firebase_user->phoneNumber != $request->phone) {
        throw new Exception('Phone number does not match with Firebase user', 409);
      }

      if (!Hash::check($request->password, $user->password)) {
        throw new Exception('Invalid credentials', 401);
      }

      if ($request->filled('device_token')) {
        $user->update(['device_token' => $request->device_token]);
      }

      $token = $user->createToken($this->random(8))->plainTextToken;

      $user->load('passenger', 'driver', 'federation');

      return $this->successResponse([
        'token' => $token,
        'user' => new UserResource($user),
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

    public function logout(Request $request)
  {
    try {
      $user = $request->user();
      
      if ($user) {
        $request->user()->currentAccessToken()->delete();
        $user->update(['device_token' => null]);
      }

      return $this->successResponse();

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function checkPhone(Request $request)
  {
    $request->validate([
      'phone' => 'required|string'
    ]);

    try {

      return $this->successResponse([
        'exists' => User::where('phone', $request->phone)->exists(),
        'phone' => $request->phone
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'old_password' => 'required|string',
      'new_password' => 'required|string|min:6|confirmed'
    ]);

    try {
      $user = $request->user();
      
      if (!Hash::check($request->old_password, $user->password)) {
        throw new Exception('Old password is incorrect', 401);
      }

      $user->update([
        'password' => Hash::make($request->new_password)
      ]);

      //$user->tokens()->delete();

      return $this->successResponse();

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function deleteAccount(Request $request)
  {
    try {

      $user = $request->user();
      $user->tokens()->delete();
      $user->delete();

      return $this->successResponse();

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }
}

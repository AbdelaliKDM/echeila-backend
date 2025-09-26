<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Constants\DriverStatus;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;



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

      return $this->successResponse();

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), $e->getCode());
    }
  }
}

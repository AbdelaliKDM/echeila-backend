<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class WilayaController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all wilayas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $wilayas = Wilaya::all();

            return $this->successResponse($wilayas);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

}
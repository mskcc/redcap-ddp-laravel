<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DataRequest;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * This web service will return data available for the specified record from
     * the source system in a standardized JSON format, which will then be interpreted by REDCap.
     * @param DataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DataRequest $request)
    {
        dd($request);

        return response()->json([], 200);

    }
}

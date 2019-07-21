<?php

namespace App\Http\Controllers\Api;

use App\DataRetrieval\DataGateway;
use App\Http\Requests\DataRequest;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    private $dataGateway;

    public function __construct(DataGateway $dataGateway)
    {
        $this->dataGateway = $dataGateway;
    }

    /**
     * This web service will return data available for the specified record from
     * the source system in a standardized JSON format, which will then be interpreted by REDCap.
     * @param DataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DataRequest $request)
    {

        $project = $request->input('project_id');

        $fieldList = collect($request->input('fields'));

        return response()->json($this->dataGateway->retrieve($fieldList, $project), 200);
    }
}

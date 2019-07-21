<?php

namespace App\Http\Controllers\Api;

use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
use App\Http\Requests\DataRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    private $dataGateway;

    public function __construct(DataGatewayInterface $dataGateway)
    {
        $this->dataGateway = $dataGateway;
    }

    /**
     * This web service will return data available for the specified record from
     * the source system in a standardized JSON format, which will then be interpreted by REDCap.
     * @param DataRequest $request
     * @return JsonResponse
     */
    public function index(DataRequest $request)
    {
        $project = $request->input('project_id');

        $fieldList = collect($request->input('fields'));

        return response()->json($this->dataGateway->retrieve($project, $fieldList), 200);
    }
}

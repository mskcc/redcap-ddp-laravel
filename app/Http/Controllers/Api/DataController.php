<?php

namespace App\Http\Controllers\Api;

use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
use App\FieldSource;
use App\Http\Requests\DataRequest;
use App\Http\Controllers\Controller;
use App\ProjectMetadata;
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

        $allMetadata = ProjectMetadata::with('fieldSource.dataSource.source.dbType')->where('project_id', $project)->get();

        $requestedData = $allMetadata->whereIn('field', $fieldList->pluck('field'));

        $json = collect();

        $requestedData->each(function($fieldMetadata) use ($json) {

            $json->add($this->dataGateway->retrieve($fieldMetadata));

        });

        return response()->json($json, 200);
    }
}

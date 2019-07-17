<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MetadataRequest;
use App\ProjectMetadata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Project;

class MetadataController extends Controller
{
    /**
     * This web service provides a list of all fields (and their attributes)
     * that are available from the source system. No input is sent to this
     * web service from REDCap.
     * @param MetadataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(MetadataRequest $request)
    {
        $metadata = ProjectMetadata::where('project_id', $request->input('project_id'))->get();
        return response()->json($metadata, 200);

    }
}

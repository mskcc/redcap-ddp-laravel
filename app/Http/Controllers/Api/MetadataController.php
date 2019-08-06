<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MetadataRequest;
use App\ProjectMetadata;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MetadataController extends Controller
{
    /**
     * This web service provides a list of all fields (and their attributes)
     * that are available from the source system. No input is sent to this
     * web service from REDCap.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        /** @var Collection $metadata */
        $metadata = ProjectMetadata::where('project_id', $request->input('project_id'))->get();

        return response()->json($metadata, 200);

    }
}

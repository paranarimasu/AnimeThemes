<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExternalResourceResource;
use App\Models\ExternalResource;

class ExternalResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/resource/{id}",
     *     operationId="getResource",
     *     tags={"Resource"},
     *     summary="Get properties of Resource",
     *     description="Returns properties of Resource",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent(ref="#/components/schemas/ExternalResourceResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource cannot be found"
     *     )
     * )
     *
     * @param  \App\Models\ExternalResource  $externalResource
     * @return \Illuminate\Http\Response
     */
    public function show(ExternalResource $resource)
    {
        return new ExternalResourceResource($resource->load('anime', 'artists'));
    }
}
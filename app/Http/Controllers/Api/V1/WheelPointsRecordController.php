<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WheelPointsRecord;
use App\Http\Requests\StoreWheelPointsRecordRequest;
use App\Http\Requests\UpdateWheelPointsRecordRequest;

class WheelPointsRecordController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWheelPointsRecordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWheelPointsRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WheelPointsRecord  $wheelPointsRecord
     * @return \Illuminate\Http\Response
     */
    public function show(WheelPointsRecord $wheelPointsRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WheelPointsRecord  $wheelPointsRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(WheelPointsRecord $wheelPointsRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWheelPointsRecordRequest  $request
     * @param  \App\Models\WheelPointsRecord  $wheelPointsRecord
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWheelPointsRecordRequest $request, WheelPointsRecord $wheelPointsRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WheelPointsRecord  $wheelPointsRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(WheelPointsRecord $wheelPointsRecord)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Equipment;
use Illuminate\Http\Request;
use App\Http\Resources\Equipment as EquipmentResource;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get equipment
        $equipment = Equipment::orderBy('created_at', 'desc')->paginate(5);

        // Return collection of equipment as a resource
        return EquipmentResource::collection($equipment);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $equipment = $request->isMethod('put') ? Equipment::findOrFail($request->equipment_id)
            : new Equipment;

        $equipment->id = $request->input('equipment_id');
        $equipment->name = $request->input('name');
        $equipment->description = $request->input('description');
        $equipment->category_id = $request->input('category_id');
        
        if ($equipment->save()) {
            # code...
            return new EquipmentResource($equipment);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get equipment
        $equipment = Equipment::findOrFail($id);

        // Return collection of equipment as a resource
        return new EquipmentResource($equipment);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get equipment
        $equipment = Equipment::findOrFail($id);

        if ($equipment->delete()) {
            # code...
            return new EquipmentResource($equipment);
        }
    }
}

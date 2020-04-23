<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use App\Http\Resources\Division as DivisionResource;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get divisions
        $divisions = Division::latest()->paginate(5);

        // Return collection of divisions as a resource
        return DivisionResource::collection($divisions);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get the division and method
        $division = $request->isMethod('put') ? Division::findOrFail($request->input('id')) : new Division;
        $division->id = $request->input('division_id');
        $division->name = $request->input('name');
        $division->description = $request->input('description');
        
        // dd($division);

        // Perform save
        if ($division->save()) {
            return new DivisionResource($division);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get division
        $division = Division::findOrFail($id);

        // Return single division as a resource
        return new DivisionResource($division);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Division $division)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get division
        $division = Division::findOrFail($id);

        // Perform delete
        if ($division->delete()) {
            return new DivisionResource($division);
        }


    }
}

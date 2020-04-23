<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\Department as DepartmentResource;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get deparment
        $department = Department::latest()->paginate(5);

        // Return collection of department as a resource
        return DepartmentResource::collection($department);
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
        // Get the department and its method
        $department = $request->isMethod('put') ? Department::findOrFail($request->input('id')) : new Department;
        $department->id = $request->input('department_id');
        $department->name = $request->input('name');
        $department->description = $request->input('description');

        // Perform save
        if ($department->save()) {
            return new DepartmentResource($department);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get department
        $department = Department::findOrFail($id);

        // Return department
        return new DepartmentResource($department);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get department
        $department = Department::findOrFail($id);

        // Perform delete
        if ($department->delete()) {
            return new DepartmentResource($department);
        }

    }
}

<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Resources\Supplier as SupplierResource;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get suppliers
        $suppliers = Supplier::latest()->paginate(5);

        // Return suppliers
        return SupplierResource::collection($suppliers);
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
        // Get supplier and its method
        $supplier = $request->isMethod('put') ? Supplier::findOrFail($request->input('id')) : new Supplier;

        // Enlist fields
        $supplier->id = $request->input('supplier_id');
        $supplier->name = $request->input('name');
        $supplier->address = $request->input('address');

        // Perform save
        if ($supplier->save()) {
            return new SupplierResource($supplier);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get supplier
        $supplier = Supplier::findOrFail($id);

        // Return supplier
        return new SupplierResource($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get supplier
        $supplier = Supplier::findOrFail($id);

        // Perform delete
        if ($supplier->delete()) {
            return new SupplierResource($supplier);
        }
    }
}

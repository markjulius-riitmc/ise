<?php

namespace App\Http\Controllers;

use App\ManageEquipment;
use App\ManageEquipmentItem;
use App\ManageEquipmentAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\ManageEquipment as ManageEquipmentResource;

class ManageEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get manage equipment
        $equipments = ManageEquipment::latest()->paginate(5);

        // Return manage equipment
        return ManageEquipmentResource::collection($equipments);
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
        // Get manage equipment and method
        $equipment = $request->isMethod('put') ? ManageEquipment::findOrFail($request->id) : new ManageEquipment;
        // Initialize fields
        $equipment->id = $request->id;
        $equipment->par_no = $request->par_no;
        $equipment->description = $request->description;
        $equipment->site_id = $request->site;
        $equipment->division_id = $request->division;
        $equipment->department_id = $request->department;
        $equipment->employee_id = $request->employee;
        $equipment->user_id = '1';

        $file_count = count(collect($request->file_paths));
        $item_count = count(collect($request->item_lists));

        // dd(json_decode($request->item_lists));
        
        // Perform save
        if ($equipment->save()) {
            if ($file_count > 0) {
                // Loop files
                foreach ($request->file_paths as $file) {
                    $filename = Str::lower(
                        pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                        .'-'
                        .uniqid()
                        .'.'
                        .$file->getClientOriginalExtension()
                    );

                    $filename = $file->hashName();

                    $file->store('dummy');
                    $attachment = $request->isMethod('put') ? ManageEquipmentAttachment::findOrFail('') : new ManageEquipmentAttachment;
                    $attachment->manage_equipment_id = $equipment->id;
                    $attachment->file_path = $filename;
                    $attachment->save();
                }
            }
     
            if ($item_count > 0) {
                // Loop item lists
                foreach (json_decode($request->item_lists, true) as $list) {
                    // Get item and method
                    $item = $request->isMethod('put') ? ManageEquipmentItem::findOrFail('') : new ManageEquipmentItem;
                    // Initialize fields
                    // $item->id = $list;
                    $item->manage_equipment_id = $equipment->id;
                    $item->equipment_id = $list['equipment'];
                    $item->property_no = $list['property_no'];
                    $item->brand = $list['brand'];
                    $item->model_no = $list['model_no'];
                    $item->serial_no = $list['serial_no'];
                    $item->sku = $list['sku'];
                    $item->supplier_id = $list['supplier'];
                    $item->save();
                }
            }   

            return new ManageEquipmentResource($equipment);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManageEquipment  $manageEquipment
     * @return \Illuminate\Http\Response
     */
    public function show(ManageEquipment $manageEquipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManageEquipment  $manageEquipment
     * @return \Illuminate\Http\Response
     */
    public function edit(ManageEquipment $manageEquipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManageEquipment  $manageEquipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManageEquipment $manageEquipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManageEquipment  $manageEquipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get manage equipment
        $equipment = ManageEquipment::findOrFail($id);

        // Perform delete
        if ($equipment->delete()) {
            return new ManageEquipmentResource($equipment);
        }

    }
}

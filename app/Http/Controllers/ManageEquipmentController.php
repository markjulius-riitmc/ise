<?php

namespace App\Http\Controllers;

use App\ManageEquipment;
use App\ManageEquipmentItem;
use App\ManageEquipmentAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file_count = count(collect($request->file_paths));
        $item_count = count(collect($request->item_lists));

        // Get manage equipment and method
        $equipment = new ManageEquipment;
        // Initialize fields
        $equipment->par_no = $request->par_no;
        $equipment->description = $request->description;
        $equipment->site_id = $request->site;
        $equipment->division_id = $request->division;
        $equipment->department_id = $request->department;
        $equipment->employee_id = $request->employee;
        $equipment->user_id = '1';
        
        // Perform save
        if ($equipment->save()) {
            if ($file_count > 0) {
                // Loop files
                foreach ($request->file_paths as $file) {
                    // $filename = Str::lower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    //     .'-'
                    //     .$file->hashname()
                    //     .'.'
                    //     .$file->getClientOriginalExtension()
                    // );
                    $file->store('dummy');
                    $attachment = new ManageEquipmentAttachment;
                    $attachment->manage_equipment_id = $equipment->id;
                    $attachment->file_path = $file->hashName();
                    $attachment->save();
                }
            }
            if ($item_count > 0) {
                // Loop item lists
                foreach (json_decode($request->item_lists, true) as $list) {
                    // Get item and method
                    $item = new ManageEquipmentItem;
                    // Initialize fields
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
            // return new ManageEquipmentResource($equipment);
            return response(['success' => 'Property Acknowledgment Receipt added'], Response::HTTP_CREATED);
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
        // dd($manageEquipment);
        return new ManageEquipmentResource($manageEquipment);
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
        // $manageEquipment->update($request->all());
        $file_count = count(collect($request->file_paths));
        $item_count = count(collect($request->item_lists));

        // dd($request->file_paths);
        // dd($request->item_lists);

        // dd($request->site);

        // Assign values
        $manageEquipment->par_no = $request->par_no;
        $manageEquipment->description = $request->description;
        $manageEquipment->site_id = $request->site;
        $manageEquipment->division_id = $request->division;
        $manageEquipment->department_id = $request->department;
        $manageEquipment->employee_id = $request->employee;
        $manageEquipment->user_id = '1';

        // Perform save
        if ($manageEquipment->save()) {
            if ($file_count > 0) {
                // Loop files
                foreach ($request->file_paths as $file) {
                    // $filename = Str::lower(
                    //     pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    //     .'-'
                    //     .uniqid()
                    //     .'.'
                    //     .$file->getClientOriginalExtension()
                    // );

                    // $filename = $file->hashName();

                    // $file->store('dummy');

                    $attachment = ManageEquipmentAttachment::findOrFail($file['id']);
                    $attachment->manage_equipment_id = $manageEquipment->id;
                    $attachment->file_path = $file['id'];
                    $attachment->save();
                }
            }
    
            if ($item_count > 0) {
                // Loop item lists
                foreach ($request->item_lists as $list) {
                    // Get item and method
                    $item = ManageEquipmentItem::findOrFail($list['id']);
                    // Initialize fields
                    $item->manage_equipment_id = $manageEquipment->id;
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
        } 
        return response(['success' => 'Property Acknowledgment Receipt updated'], Response::HTTP_ACCEPTED);
        // return new ManageEquipmentResource($equipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManageEquipment  $manageEquipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($manageEquipment)
    {
        // Get manage equipment
        // $equipment = ManageEquipment::findOrFail($id);

        // Perform delete
        if ($manageEquipment->delete()) {
            return new ManageEquipmentResource($equipment);
        }

    }
}

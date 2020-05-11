<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\ManageEquipmentAttachment;
use App\ManageEquipmentItem;

class ManageEquipment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'par_no' => $this->par_no,
            'description' => $this->description,
            'site_id' => $this->site_id,
            'division_id' => $this->division_id,
            'department_id' => $this->department_id,
            'employee_id' => $this->employee_id,
            'user_id' => User::findOrFail($this->user_id)->value('name'),
            'created_at' => $this->created_at->diffForHumans(),
            'attachments' => ManageEquipmentAttachment::where('manage_equipment_id', $this->id)->get(),
            'items' => ManageEquipmentItem::where('manage_equipment_id', $this->id)->select('id', 'equipment_id as equipment', 'property_no', 'brand', 'model_no', 'serial_no', 'sku', 'supplier_id as supplier', 'quantity', 'unit_value')->get(),
        ];
    }
}

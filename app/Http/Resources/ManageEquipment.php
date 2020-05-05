<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'employee_id' => $this->employee_ud,
            'user_id' => $this->user_id,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\EmployeeAddress */
class EmployeeAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'residence_country' => $this->residence_country,
            'residence_zip_code' => $this->residence_zip_code,
            'residence_city' => $this->residence_city,
            'residence_street' => $this->residence_street,
            'residence_building_number' => $this->residence_building_number,
            'residence_apartment_number' => $this->residence_apartment_number,
            'different_correspondence_address' => $this->different_correspondence_address,
            'correspondence_country' => $this->correspondence_country,
            'correspondence_zip_code' => $this->correspondence_zip_code,
            'correspondence_city' => $this->correspondence_city,
            'correspondence_street' => $this->correspondence_street,
            'correspondence_building_number' => $this->correspondence_building_number,
            'correspondence_apartment_number' => $this->correspondence_apartment_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'employee' => new EmployeeResource($this->whenLoaded('employee')),
        ];
    }
}

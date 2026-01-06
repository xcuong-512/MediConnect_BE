<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $avatarUrl = $this->avatar
            ? asset('storage/' . $this->avatar)
            : "https://via.placeholder.com/300x300?text=No+Image";

        return [
            'id' => $this->id,
            'staff_code' => $this->staff_code,
            'full_name' => $this->person ? $this->person->full_name : 'N/A',
            'avatar' => $avatarUrl,
            'departments' => $this->departments->pluck('name'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'roll_no' => $this->roll_no,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'department' => $this->department,
            'year_of_study' => $this->year_of_study,
            'status' => $this->status,
            'active_borrowings_count' => $this->when(
                $this->relationLoaded('activeBorrowings'),
                $this->activeBorrowings->count()
            )
        ];
    }
}

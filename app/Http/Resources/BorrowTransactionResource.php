<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowTransactionResource extends JsonResource
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
            'student_id' => $this->student_id,
            'book_id' => $this->book_id,
            'borrow_date' => $this->borrow_date->format('Y-m-d H:i:s'),
            'due_date' => $this->due_date->format('Y-m-d H:i:s'),
            'return_date' => $this->return_date?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'is_overdue' => $this->isOverdue(),
            'student' => new StudentResource($this->whenLoaded('student')),
            'book' => new BookResource($this->whenLoaded('book')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}

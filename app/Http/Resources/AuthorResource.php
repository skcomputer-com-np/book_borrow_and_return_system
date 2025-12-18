<?php

namespace App\Http\Resources;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'author_name' => $this->full_name,
            'bio' => $this->bio,
            'books' => $this->when($this->relationLoaded('books'), $this->books->count())
        ];
    }
}
